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

        $query = $this->db->get_where($this->table, array('thread_id' => $slug));
        return $query->row_array();
    }

    // 範囲を指定してスレッドを取得
    public function get_threads_limit($limit, $start)
    {
        // $limitに表示する最大数 $startに開始位置
        $this->db->limit($limit, $start);
        // スレッド作成日時で並び替え(降順)
        $this->db->order_by('creation_datetime','DESC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    // 指定ユーザーの作成スレッド数を取得
    public function get_user_count($slug = FALSE)
    {
        // $slugが指定されていない場合0を返却
        if($slug === FALSE){ return 0; }

        $this->db->where('creator_id', $slug);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // スレッド作成 
    public function create_thread($creator_id = FALSE)
    {
        // $slugが指定されていない場合0を返却
        if($creator_id === FALSE){ return 0; }
        $data = array(
            'title' => $this->input->post('title'),
            'creation_datetime' => date('Y/m/d H:i:s'),
            'creator_id' => $creator_id
        );
        $this->db->insert($this->table, $data);
        // 挿入したデータのIDを返却
        return $this->db->insert_id();
    }
}