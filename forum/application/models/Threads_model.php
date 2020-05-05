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

    // 全スレッド数を取得 $slugが指定されていた場合検索に引っかかった数を取得
    public function get_thread_count($slug = NULL)
    {
        if(!empty($slug)){
            $this->db->like('title', $slug);
        }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // 範囲を指定してスレッド、コメント数の取得
    // 引数1:取得するデータの最大数 引数2:取得を開始するデータ位置
    // 引数3:並び替えのキー 初期値がスレッド作成日
    // 引数4:降順("DESC") or 昇順("DESC") 初期値が降順
    public function get_threads_range($limit, $start, 
        $sort_key = 'creation_datetime', $sort_order = 'DESC', $key_word = NULL)
    {
        /*
        [SQL文]
        SELECT threads.thread_id, threads.title, threads.creation_datetime,
                threads.creator_id,IFNULL(count(comments.thread_id),0) AS comment_count
            FROM threads 
            LEFT JOIN comments ON threads.thread_id = comments.thread_id 
            WHERE threads.title LIKE "%$key_word%"
            GROUP BY threads.thread_id 
            ORDER BY $sort_key $sort_order, threads.thread_id;
            LIMIT  $limit
            OFFSET $start
        */

        // $limitに表示する最大数 $startに開始位置
        $this->db->limit($limit, $start);

        // threadsテーブルのすべての列と、コメント数を集計したcomment_count列を取得
        // IFNULLでコメントが無いスレッドは0に
        $this->db->select('threads.thread_id, threads.title, threads.creation_datetime, threads.creator_id,,IFNULL(count(comments.thread_id),0) AS comment_count',FALSE);
        $this->db->from($this->table);
        // commentsテーブルと外部結合(LEFT OUTER JOIN)
        $this->db->join('comments','threads.thread_id = comments.thread_id','left outer');
        // 
        if(!empty($key_word))
        {
            $this->db->like('`threads.title`', $key_word);
        }
        // スレッドIDでグループ化
        $this->db->group_by('threads.thread_id',FALSE);
        // 並び替え第二はスレッドIDで昇順
        $this->db->order_by("{$sort_key} {$sort_order} ,`threads.thread_id` ASC");
        // クエリ取得
        $query = $this->db->get();
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