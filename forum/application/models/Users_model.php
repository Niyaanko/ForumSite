<?php 
class Users_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function regist_user()
    {
        $nickname = '名無しさん';
        // パスワードではハッシュ化を行う
        $data = array(
            'nickname' => $nickname,
            'mailaddress' => $this->input->post('mailaddress'),
            'password' => $this->regist_password_hash($this->input->post('password')),
            'permission' => 1
        );
        return $this->db->insert('users', $data);
    }

    public function get_user($user_id)
    {
        if(is_null($user_id))
        {
            return NULL;
        }
        // SELECT * FROM users WHERE user_id = 引数のuser_id
        $query = $this->db->get_where('users', array('user_id' => $user_id));
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

    public function login_user()
    {
        // フォームから受け取ったメールアドレスのuserを取得
        $query = $this->db->get_where('users', array('mailaddress' => $this->input->post('mailaddress')));
        $user = $query->row_array();
        // 受け取った値がNULLだった場合(該当するメールアドレスのuserがいなかった場合)NULLを返却
        if(is_null($user)){
            return NULL;
        }
        // パスワードがDBに格納されているハッシュ化されたパスワードと一致するか判定 一致しなかった場合NULLを返却
        $input_password = $this->input->post('password');
        $hash_password = $user['password'];
        $is_correct = password_verify($input_password, $hash_password);
        if($is_correct === FALSE){
            return NULL;    
        }
        //passwordは必要ないので削除
        unset($user['password']);
        // userを返却
        return $user;
    }
    // 暗号化方法を隠蔽
    private function regist_password_hash($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }
}