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

    // // 範囲を指定してコメントを取得
    // public function get_comments_limit($limit = FALSE, $start = FALSE,$slug = FALSE)
    // {
    //     if($limit === FALSE || $start === FALSE || $thread_id = FALSE)
    //     {
    //         return NULL;
    //     }
        
    //     // $limitに表示する最大数 $startに開始位置
    //     $this->db->limit($limit, $start);

    //     // コメントIDで並び替え(昇順)
    //     $this->db->order_by('comment_id','ASC');
    //     $query = $this->db->get_where($this->table, array('thread_id' => $slug));
    //     return $query->result_array();
    // }

    // 範囲を指定してコメントを取得
    public function get_comments_limit($limit = FALSE, $start = FALSE, $slug = FALSE, $user_id = FALSE)
    {
        if($limit === FALSE || $start === FALSE || $slug === FALSE || $user_id === FALSE)
        {
            return NULL;
        }
        
        // $limitに表示する最大数 $startに開始位置
        $this->db->limit($limit, $start);
        // IFNULLで通報してないコメントは0に
        $sql_select = 'comments. comment_id, comments.text, comments.comment_datetime,';
        $sql_select .= 'comments.commenter_id, comments.thread_id, IFNULL(reports.report_id,0) AS reported';
        $this->db->select($sql_select,FALSE);
        $this->db->from($this->table);
        // reportsテーブルと外部結合(LEFT OUTER JOIN)
        $this->db->join('reports','comments.comment_id = reports.comment_id AND reports.reporter_id ='.$user_id,'left outer');
        // コメントIDで並び替え(昇順)
        $this->db->order_by('comment_datetime','ASC');
        $this->db->where('thread_id', $slug);
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
            'thread_id' => $thread_id
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

}