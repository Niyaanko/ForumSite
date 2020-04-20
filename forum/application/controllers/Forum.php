<?php
class Forum extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager'));
        $this->load->library('session');
        $this->load->helper(array('cookie','url'));
    }

    public function view()
    {
        // user_idからパスワード以外のユーザ情報を取得
        $user = $this->users_model->get_user($_SESSION['user_id']);
        if(!(is_null($user))){
            $data['user'] = $user;
        }
        $this->load->view('header', $data);
        $this->load->view('top_page',$data);
        $this->load->view('footer', $data);
    }

}