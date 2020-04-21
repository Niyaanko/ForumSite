<?php
class Forum extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager'));
        $this->load->library('session');
        $this->load->helper('url','url_helper');
    }

    public function view()
    {
        $data['title'] = 'イグナイト - トップページ';
        // user_idからパスワード以外のユーザ情報を取得
        $user = $this->users_model->get_user($_SESSION['user_id']);
        if(!(is_null($user))){
            $data['user'] = $user;
        }
        $this->load->view('header', $data);
        $this->load->view('top_page',$data);
        $this->load->view('footer', $data);
    }

    public function logout()
    {
        $this->session_manager->deleteSession();
        redirect(site_url("login/login"));
    }

}