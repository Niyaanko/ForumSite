<?php 
class Threads_model extends CI_Model {

    protected $table = 'threads';

    public function __construct()
    {
        $this->load->database();
    }

    // slug(引数)がNULLだった場合、全スレッド情報を取得
    // slug(引数)が指定されていた場合、指定されたスレッドの情報を取得
    public function get_threads($slug = FALSE)
    {
        //全スレッド情報を取得
        if($slug === FALSE)
        {
            $query = $this->db->get($this->table);
            return $query->result_array();
        }

        $query = $this->db->get($this->table, array('thread_id' => $slug));
        return $query->row_array();
    }

    // limitに取得するスレッド数を指定 startに開始データ
    public function get_threads_limit($limit, $start)
    {
        // 
        $this->db->limit($limit, $start);
        // スレッド作成日時で並び替え(降順)
        $this->db->order_by('creation_datetime','DESC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

}