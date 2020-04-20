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

    public function login_user()
    {
        // フォームから受け取ったメールアドレスのuserを取得
        $user = $this->db->get_where('users', array('mailaddress' => $this->input->post('mailaddress')));

        // 受け取った値がNULLだった場合(該当するメールアドレスのuserがいなかった場合)NULLを返却
        if(is_null($user)){
            return NULL;
        }

        // パスワードがDBに格納されているハッシュ化されたパスワードと一致するか判定 一致しなかったっ場合NULLを返却
        if($this->password_verify($this->input->post('password'),$user['password']) === FALSE){
            return NULL;
        }

        // user_idを返却
        return $user['user_id'];

    }
    // 暗号化方法を隠蔽
    private function regist_password_hash($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

}