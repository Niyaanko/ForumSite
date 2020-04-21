<?php
class Forum extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager'));
        $this->load->library('session');
        $this->load->helper('url_helper');
    }

    public function view()
    {
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE){
            $this->session_manager->deleteSession();
            redirect(site_url("login/login"));
        }

        // スレッド情報の取得
        
        // それぞれのスレッドのコメント数取得

        $data['title'] = 'イグナイト - トップページ';
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