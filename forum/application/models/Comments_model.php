<?php 
class Comments_model extends CI_Model {

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
        $query = $this->db->get_where('comments', array('thread_id' => $slug));
        return $query->result_array();
    }

}