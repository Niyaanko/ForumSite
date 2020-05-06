<?php 
class Reports_model extends CI_Model {

    protected $table = 'reports'; 

    public function __construct()
    {
        $this->load->database();
    }

    // $comment_idに指定されたコメントを通報者：$user_idで通報リストに追加
    public function report_comment($comment_id = FALSE,$user_id = FALSE)
    {
        if($comment_id === FALSE || $user_id === FALSE)
        {
            return FALSE;
        }
        $data = array(
            'comment_id' => $comment_id,
            'reporter_id' => $user_id
        );
        $this->db->insert($this->table, $data);
        return TRUE;
    }

        // 通報されたコメントを範囲指定して取得
        public function get_report_comments($limit = FALSE, $start = FALSE, $sort_key = 'report_count', $sort_order = 'DESC')
        {

            /*
            [SQL文]
            SELECT reports.comment_id, comments.text AS comment_text, comments.comment_datetime, 
                comments.commenter_id ,users.nickname AS commenter_name, threads.thread_id, 
                threads.title AS thread_title, COUNT(reports.comment_id) AS report_count 
            FROM reports 
            INNER JOIN comments ON reports.comment_id = comments.comment_id 
            INNER JOIN threads ON comments.thread_id = threads.thread_id 
            INNER JOIN users ON comments.commenter_id = users.user_id 
            GROUP BY reports.comment_id 
            ORDER BY $sort_key $sort_order, report_count DESC;
            LIMIT $limit
            OFFSET $start;
            */

            if($limit === FALSE || $start === FALSE)
            {
                return NULL;
            }
            
            // $limitに表示する最大数 $startに開始位置
            $this->db->limit($limit, $start);
            // COUNT(reports.comment_id)でそのコメントが通報された数を集計
            $sql_select = 'reports.comment_id, comments.text AS comment_text, comments.comment_datetime,';
            $sql_select .= 'comments.commenter_id ,users.nickname AS commenter_name, threads.thread_id,';
            $sql_select .= 'threads.title AS thread_title, COUNT(reports.comment_id) AS report_count';
            $this->db->select($sql_select,FALSE);
            $this->db->from($this->table);
            // commentsテーブルと内部結合(INNER JOIN)
            $this->db->join('comments','reports.comment_id = comments.comment_id','inner');
            // threadsテーブルと内部結合(INNER JOIN)
            $this->db->join('threads','comments.thread_id = threads.thread_id','inner');
            // usersテーブルと内部結合(INNER JOIN)
            $this->db->join('users','comments.commenter_id = users.user_id','inner');
            // スレッドIDでグループ化
            $this->db->group_by('reports.comment_id');
            // 並び替え
            $this->db->order_by("{$sort_key} {$sort_order}","comments.comment_id ASC");
            $query = $this->db->get();
            return $query->result_array();
        }

        // 通報されたコメントの合計数(通報が重複しないようにGROUP BY)を取得
        public function get_report_count()
        {
            // スレッドIDでグループ化
            $this->db->group_by('comment_id');
            return $this->db->count_all_results($this->table);
        }
}