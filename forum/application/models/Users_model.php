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
            'permission' => 1
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

    // 暗号化方法を隠蔽
    private function regist_password_hash($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }
}