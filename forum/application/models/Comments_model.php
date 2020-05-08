<?php 
class Comments_model extends CI_Model {

    protected $table = 'comments'; 

    public function __construct()
    {
        $this->load->database();
    }

    // 指定されたスレッドの全コメント情報を取得
    public function get_comments($slug = FALSE)
    {

        // 引数が指定されていなかった場合NULLを返す
        if($slug === FALSE)
        {
            return NULL;
        }
        $query = $this->db->get_where($this->table, array('thread_id' => $slug));
        return $query->result_array();
    }

    // 指定ユーザーの投稿コメント数を取得
    public function get_user_count($slug = FALSE)
    {
        // $slugが指定されていない場合0を返却
        if($slug === FALSE){ return 0; }

        $this->db->where('commenter_id', $slug);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    // 指定スレッドのコメント数を取得
    public function get_thread_count($slug = FALSE)
    {
        // $slugが指定されていない場合0を返却
        if($slug === FALSE){ return 0; }

        $this->db->where('thread_id', $slug);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // 範囲を指定してコメント,付随する情報を取得
    public function get_comments_limit($limit = FALSE, $start = FALSE, $slug = FALSE, $user_id = FALSE)
    {
        /*[SQL文]
        SELECT comments. comment_id, comments.text, comments.comment_datetime, comments.status,
            comments.commenter_id, comments.thread_id, users.nickname, users.permission,
            IFNULL(reports.report_id,0) AS reported
        FROM comments
        LEFT OUTER JOIN reports ON comments.comment_id = reports.comment_id AND reports.reporter_id = $user_id 
        INNER JOIN users ON comments.commenter_id = users.user_id
        WHERE thread_id = $slug
        ORDER BY comment_datetime ASC
        LIMIT $limit
        OFFSET $start;
        */
        if($limit === FALSE || $start === FALSE || $slug === FALSE || $user_id === FALSE)
        {
            return NULL;
        }
        
        // IFNULLで通報してないコメントは0に
        $sql_select = 'comments. comment_id, comments.text, comments.comment_datetime, comments.status,';
        $sql_select .= 'comments.commenter_id, comments.thread_id, users.nickname, users.permission,';
        $sql_select .= 'IFNULL(reports.report_id,0) AS reported';
        $this->db->select($sql_select,FALSE);
        $this->db->from($this->table);
        // reportsテーブルと外部結合(LEFT OUTER JOIN)
        $this->db->join('reports','comments.comment_id = reports.comment_id AND reports.reporter_id ='.$user_id,'left outer');
        // usersテーブルと内部結合(INNER JOIN)
        $this->db->join('users','comments.commenter_id = users.user_id','inner');
        $this->db->where('thread_id', $slug);
        // コメントIDで並び替え(昇順)
        $this->db->order_by('comment_datetime','ASC');
        // $limitに表示する最大数 $startに開始位置
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_comments($commenter_id = FALSE, $thread_id = FALSE)
    {
        // $slugが指定されていない場合0を返却
        if($commenter_id === FALSE || $thread_id === FALSE){ return 0; }
        $data = array(
            'text' => $this->input->post('comment'),
            'comment_datetime' => date('Y/m/d H:i:s'),
            'commenter_id' => $commenter_id,
            'thread_id' => $thread_id,
            'status' => 'NORMAL'
        );
        $this->db->insert($this->table, $data);
        // 挿入したデータのIDを返却
        return $this->db->insert_id();
    }

    // 指定されたコメント情報を取得
    public function get_comment($slug = FALSE)
    {
        // 引数が指定されていなかった場合NULLを返す
        if($slug === FALSE)
        {
            return NULL;
        }
        $query = $this->db->get_where($this->table, array('comment_id' => $slug));
        return $query->row_array();
    }

    // コメントのステータスをdeleteに
    public function delete_comment($slug = FALSE)
    {
        // 引数が指定されていなかった場合NULLを返す
        if($slug === FALSE)
        {
            return NULL;
        }
        $data = array('status' => 'DELETED');
        $this->db->where('comment_id', $slug);
        $this->db->update($this->table, $data);
        // TRUEを返却
        return TRUE;
    }

}