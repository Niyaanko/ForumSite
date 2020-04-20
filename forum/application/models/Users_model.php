<?php 
class Users_model extends CI_model {

    public function __construct()
    {
        $this->load->database();
    }

    public function regist_account()
    {
        $nickname = '名無しさん';
        $data = array(
            'nickname' => $nickname,
            'mailaddress' => $this->input->post('mailaddress'),
            'password' => $this->regist_password_hash($this->input->post('password')),
            'permission' => 1
        );
        return $this->db->insert('users', $data);
    }

    // 暗号化方法を隠蔽
    private function regist_password_hash($pass)
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    // パスワードが一致していればTrue 一致していなければFalseを返却
    private function login_password_verify($pass, $hash)
    {
        return password_verify($pass, $hash);
    }

}