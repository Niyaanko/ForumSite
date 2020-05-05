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
}