<?php 
class Threads_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    //slug(引数)がNULLだった場合、全スレッド情報を取得
    //slug(引数)が指定されていた場合、指定されたスレッドの情報を取得
    public function get_threads($slug = FALSE)
    {
        //全スレッド情報を取得
        if($slug === FALSE)
        {
            $query = $this->db->get('threads');
            return $query->result_array();
        }

        $query = $this->db->get('threads', array('thread_id' => $slug));
        return $query->row_array();
    }

}