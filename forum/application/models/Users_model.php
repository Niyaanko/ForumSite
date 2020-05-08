<?php 
class Users_model extends CI_Model {

    protected $table = 'users';

    public function __construct()
    {
        $this->load->database();
    }

    // ユーザー登録
    public function regist_user()
    {
        $nickname = '名無し';
        // パスワードではハッシュ化を行う
        $data = array(
            'nickname' => $nickname,
            'mailaddress' => $this->input->post('mailaddress'),
            'password' => $this->regist_password_hash($this->input->post('password')),
            'permission' => 'NORMAL'
        );
        $this->db->insert($this->table, $data);
    }

    // ユーザー取得(user_idから)
    public function get_user($user_id)
    {
        if(is_null($user_id))
        {
            return NULL;
        }
        // SELECT * FROM users WHERE user_id = 引数のuser_id
        $query = $this->db->get_where($this->table, array('user_id' => $user_id));
        $user = $query->row_array();
        // 受け取った値がNULLだった場合(該当するuser_idのuserがいなかった場合)NULLを返却
        if(is_null($user)){
            return NULL;
        }
        //passwordは必要ないので削除
        unset($user['password']);
        // userを返却
        return $user;
    }

    // ログイン
    public function login_user()
    {
        // フォームから受け取ったメールアドレスのuserを取得
        $query = $this->db->get_where($this->table, array('mailaddress' => $this->input->post('mailaddress')));
        $user = $query->row_array();
        // 受け取った値がNULLだった場合(該当するメールアドレスのuserがいなかった場合)NULLを返却
        if(is_null($user)){
            return NULL;
        }
        // パスワードがDBに格納されているハッシュ化されたパスワードと一致するか判定 一致しなかった場合NULLを返却
        $password_input = $this->input->post('password');
        $password_hash = $user['password'];
        $is_correct = password_verify($password_input, $password_hash);
        if($is_correct === FALSE){
            return NULL;    
        }
        //passwordは必要ないので削除
        unset($user['password']);
        // userを返却
        return $user;
    }

    // パスワード更新
    public function update_password($user_id = FALSE)
    {
        // ユーザーIDが渡されなかった場合FALSEを返却
        if($user_id === FALSE)
        { 
            return FALSE; 
        }
        // 指定されたuser_idのuserを取得 自クラスのget_userはパスワードを削除するため使用しない
        $query = $this->db->get_where($this->table, array('user_id' => $user_id));
        $user = $query->row_array();
        // 受け取った値がNULLだった場合(該当するuser_idのuserがいなかった場合)FALSEを返却
        if(is_null($user)){
            return FALSE;
        }
        // パスワードがDBに格納されているハッシュ化されたパスワードと一致するか判定 一致しなかった場合NULLを返却
        $password_input = $this->input->post('password_conf');
        $password_hash = $user['password'];
        $is_correct = password_verify($password_input, $password_hash);
        if($is_correct === FALSE){
            return NULL;    
        }
        // UPDATEするデータを渡す
        $password_new = $this->input->post('password_new');
        $data = array('password' => $this->regist_password_hash($password_new));
        // UPDATE文の実行
        $this->db->where('user_id',$user['user_id']);
        $this->db->update($this->table, $data);
        // TRUEを返却
        return TRUE;
    }

    // ニックネーム更新
    public function update_nickname($user_id = FALSE)
    {
        // ユーザーIDが渡されなかった場合FALSEを返却
        if($user_id === FALSE)
        { 
            return FALSE; 
        }
        // UPDATEするデータを渡す
        $nickname_new = $this->input->post('nickname');
        $data = array('nickname' => $nickname_new);
        // UPDATE文の実行
        $this->db->where('user_id', $user_id);
        $this->db->update($this->table, $data);
        // TRUEを返却
        return TRUE;
    }

    // メールアドレス更新
    public function update_mailaddress($user_id = FALSE)
    {
        // ユーザーIDが渡されなかった場合FALSEを返却
        if($user_id === FALSE)
        { 
            return FALSE; 
        }
        // UPDATEするデータを渡す
        $mailaddress_new = $this->input->post('mailaddress');
        $data = array('mailaddress' => $mailaddress_new);
        // UPDATE文の実行
        $this->db->where('user_id', $user_id);
        $this->db->update($this->table, $data);
        // TRUEを返却
        return TRUE;
    }

    // 指定ユーザーをBAN(permissionを-1に変更)
    public function ban_user($user_id = NULL)
    {

        // ユーザーIDが渡されなかった場合FALSEを返却
        if($user_id === NULL)
        { 
            return FALSE; 
        }
        $data = array('permission' => 'BANNED');
        // UPDATE文の実行
        $this->db->where('user_id', $user_id);
        $this->db->update($this->table, $data);
        // TRUEを返却
        return TRUE;
    }

    // 総ユーザー数を取得
    public function get_user_count($slug = FALSE)
    {
        // $slugが指定されていない場合0を返却
        if($slug === FALSE){ return 0; }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // ユーザーとそれに付随する情報を範囲指定で取得
    public function get_users_info($limit = FALSE, $start = FALSE,$sort_key = 'user_id',$sort_order = 'ASC')
    {
        /*[SQL文]
        SELECT user_id, nickname, mailaddress, permission,
            COUNT(DISTINCT comments.comment_id) AS comment_count,
            COUNT(DISTINCT threads.thread_id) AS threads_create_count,
            COUNT(DISTINCT reports.report_id) AS report_count,
            COUNT(DISTINCT comments.status = 'DELETED' OR NULL) AS deleted_comment
        FROM users
        LEFT OUTER JOIN comments ON users.user_id = comments.commenter_id
        LEFT OUTER JOIN threads ON users.user_id = threads.creator_id
        LEFT OUTER JOIN reports ON comments.comment_id = reports.comment_id
        GROUP BY users.user_id
        ORDER BY $sort_key $sort_order, users.user_id ASC
        LIMIT $limit
        OFFSET $start;*/
        if($limit === FALSE || $start === FALSE){
            return NULL;
        }
        $sql_select = 'user_id, nickname, mailaddress, permission,';
        $sql_select .= 'COUNT(DISTINCT comments.comment_id) AS comment_count,';
        $sql_select .= 'COUNT(DISTINCT threads.thread_id) AS threads_create_count,';
        $sql_select .= 'COUNT(DISTINCT reports.report_id) AS report_count,';
        $sql_select .= "COUNT(DISTINCT comments.status = 'DELETED' OR NULL) AS deleted_comment";
        $this->db->select($sql_select,FALSE);
        $this->db->from($this->table);
        // commentsテーブルと外部結合(LEFT OUTER JOIN)
        $this->db->join('comments','users.user_id = comments.commenter_id','left outer');
        // threadsテーブルと外部結合(LEFT OUTER JOIN)
        $this->db->join('threads','users.user_id = threads.creator_id','left outer');
        // reportsテーブルと外部結合(LEFT OUTER JOIN)
        $this->db->join('reports','comments.comment_id = reports.comment_id','left outer');
        // user_idでグループ化
        $this->db->group_by('users.user_id');
        // $sort_key $sort_order, users.user_id ASC
        $this->db->order_by("{$sort_key} {$sort_order} ,`users.user_id` ASC");
        $query = $this->db->get();
        return $query->result_array();
    }

    // 暗号化方法を隠蔽
    private function regist_password_hash($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }
}