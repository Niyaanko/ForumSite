<?php
class Mypage extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','threads_model','comments_model','session_manager'));
        $this->load->library(array('session','form_validation'));
        $this->load->helper('url_helper');
    }

    // ユーザーのマイページ表示
    public function mypage()
    {
        // $order で並び替え方法を指定 NULLの場合はスレッド作成日(降順)
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE)
        {
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
    // パスワード以外の変更ボタン(ニックネーム、メールアドレス)が押された場合の処理
    public function change($slug = NULL){

        // $order で並び替え方法を指定 NULLの場合はスレッド作成日(降順)
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE)
        {
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }

        

        // タイトルを渡す
        $data['title'] = 'イグナイト - パスワード変更';
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'my_style.css';
        // トップページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('change_page', $data);
        $this->load->view('footer', $data);
    }

    // パスワードの変更ボタンが押された場合の処理
    public function pw_change(){

        // $order で並び替え方法を指定 NULLの場合はスレッド作成日(降順)
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE)
        {
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }

        // 検証ルールの指定
        $config = 
            array(
                array(
                    'field' => 'password_conf',
                    'label' => '古いパスワード',
                    'rules' => 'required|min_length[8]|max_length[12]',
                    'errors' => 
                    array(
                        'required' => '%s を入力していません',
                    )
                ),
                array(
                    'field' => 'password_new',
                    'label' => '新しいパスワード',
                    'rules' => 'required|min_length[8]|max_length[12]|differs[password_conf]',
                    'errors' => 
                    array(
                        'required' => '%s を入力していません',
                        'min_length' => '%s は8文字以上で入力して下さい',
                        'max_length' => '%s は12文字以内で入力して下さい',
                        'differs' => '新しいパスワードは古いパスワードと異なるものを入力してください'
                    )
                )
            );
        
        // 検証ルールの反映
        $this->form_validation->set_rules($config);

        // submit 前や、不正な入力のときはフォームを表示する
        if($this->form_validation->run() === FALSE)
        {
            $this->view_password_page();
        }
        // 正しく入力された場合、UPDATEメソッドを呼び出す UPDATEに成功した場合TRUE失敗した場合FALSE
        // を$data['success']にセットしパスワード変更ページを呼び出し
        else
        {
            $user = $_SESSION['user'];
            $data['success'] = $this->users_model->update_password($user['user_id']);
            $this->view_password_page($data);
        }
    }
    // パスワード変更ページ表示メソッド
    public function view_password_page($data = NULL){
        if(empty($data))
        {
            $data = array();
        }
        // タイトルを渡す
        $data['title'] = 'イグナイト - パスワード変更';
        // パスワード変更画面のCSSを渡す
        $data['stylesheet'] = 'password_style.css';
        // パスワード変更画面を表示する
        $this->load->view('header', $data);
        $this->load->view('password_page', $data);
        $this->load->view('footer', $data);
    }

    public function logout()
    {
        $this->session_manager->deleteSession();
        redirect(site_url('login/login'));
    }

}