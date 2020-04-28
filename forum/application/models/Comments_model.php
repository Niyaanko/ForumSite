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

    // 指定スレッドの合計コメント数を取得
    public function get_count($slug = FALSE)
    {
        // $slugが指定されていない場合0を返却
        if($slug === FALSE){ return 0; }

        $this->db->where('thread_id', $slug);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

}