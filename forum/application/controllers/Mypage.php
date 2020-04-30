<?php
class Mypage extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','threads_model','comments_model','session_manager'));
        $this->load->library('session');
        $this->load->helper('url_helper');
    }

    // ユーザーのマイページ表示
    public function mypage()
    {
        // $order で並び替え方法を指定 NULLの場合はスレッド作成日(降順)
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE){
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }
        $user = $_SESSION['user'];
        // 作成スレッド数の取得、セット
        $data['threads_count'] = $this->threads_model->get_user_count($user['user_id']);
        // 投稿コメント数の取得、セット
        $data['comments_count'] = $this->comments_model->get_user_count($user['user_id']);
        
        // タイトルを渡す
        $data['title'] = 'イグナイト - マイページ';
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'my_style.css';
        // トップページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('my_page', $data);
        $this->load->view('footer', $data);
    }

    public function logout()
    {
        $this->session_manager->deleteSession();
        redirect(site_url('login/login'));
    }

}