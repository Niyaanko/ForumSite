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

        $this->session_judge();

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

        $this->session_judge();

        // $slug がnullの場合マイページへ遷移
        if(empty($slug)){
            redirect(site_url('mypage/mypage'));
        }
        $user = $_SESSION['user'];    

        // 作成スレッド数の取得、セット
        $data['threads_count'] = $this->threads_model->get_user_count($user['user_id']);
        // 投稿コメント数の取得、セット
        $data['comments_count'] = $this->comments_model->get_user_count($user['user_id']);
        
        // $slugがnicknameの場合
        if($slug === 'nickname')
        {
            $data['change_label'] = 'ニックネーム';
            $data['slug'] = 'nickname';
            // 検証ルールの指定
            $config = array( 
                array(
                    'field' => 'nickname',
                    'label' => 'ニックネーム',
                    'rules' => 'required|max_length[10]|callback_not_equal['.$user['nickname'].']',
                    'errors' => 
                    array(
                        'required' => '%s を入力していません',
                        'max_length' => '%s は10文字以内で入力して下さい',
                        'not_equal' => '古いニックネームと異なるものを入力してください'
                    )
                ),
                array(
                    'field' => 'mailaddress',
                    'label' => 'メールアドレス'
                )
            );
            // ニックネーム変更のUPDATE文を実行する無名関数の代入
            $func_update = function($func_user_id)
            {
                return $this->users_model->update_nickname($func_user_id);
            };
            
        }
        // $slugがnicknameの場合
        elseif($slug === 'mailaddress')
        {            
            $data['change_label'] = 'メールアドレス';
            $data['slug'] = 'mailaddress';
            // 検証ルールの指定
            $config = array(
                array(
                    'field' => 'nickname',
                    'label' => 'ニックネーム'
                ),
                array(
                    'field' => 'mailaddress',
                    'label' => 'メールアドレス',
                    'rules' => 'required|max_length[90]|is_unique[users.mailaddress]|valid_email|callback_not_equal['.$user['mailaddress'].']',
                    'errors' => array(
                        'required' => '%s を入力していません',
                        'max_length' => '%s は90文字以内で入力して下さい',
                        'is_unique' => '%s は既に使用されています',
                        'valid_email' => 'メールアドレスを入力して下さい',
                        'not_equal' => '古いメールアドレスと異なるものを入力してください'
                    )
                )
            );
            // メールアドレス変更のUPDATE文を実行する無名関数の代入
            $func_update = function($func_user_id)
            {
                return $this->users_model->update_mailaddress($func_user_id);
            };
        }
        // $slugがそれ以外の場合 マイページへ遷移
        else
        {
            show_404();
        }
        // 検証ルールの反映
        $this->form_validation->set_rules($config);
        

        // submit 前や、不正な入力のときはフォームを表示する
        if($this->form_validation->run() === FALSE) 
        {
            $this->view_change_page($data);
        }
        // 正しく入力された場合、UPDATEメソッドを呼び出す UPDATEに成功した場合TRUE、失敗した場合FALSE
        // その後マイページを呼び出し
        else
        {
            $success = $func_update($user['user_id']);
            // UPDATEが成功した場合データをセッションにセット
            if($success === TRUE)
            {
                $changed_user = $this->users_model->get_user($user['user_id']);
                $this->session_manager->addSession($changed_user);
                redirect(site_url('mypage/mypage'));
            }else{
                $this->view_change_page($data);
            }
        }
    }

    // パスワードの変更ボタンが押された場合の処理
    public function pw_change(){

        $this->session_judge();

        // 検証ルールの指定
        $config = array(
            array(
                'field' => 'password_conf',
                'label' => '古いパスワード',
                'rules' => 'required',
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
    public function view_change_page($data)
    {
        if(empty($data))
        {
            redirect(site_url('mypage/mypage'));
        }
        // タイトルを渡す
        $data['title'] = 'イグナイト - '.$data['change_label'].'変更';
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'change_style.css';
        // トップページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('change_page', $data);
        $this->load->view('footer', $data);
    }

    // 追加の検証ルール用メソッド
    // 入力内容と現在のパラメータが同一であった場合FALSE
    public function not_equal($input_str,$param_str)
    {
        if($input_str === $param_str)
        {
            $this->form_validation->set_message('not_equal', 'Do not enter the same as the old parameter.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function logout()
    {
        $this->session_manager->deleteSession();
        redirect(site_url('login/login'));
    }

    public function session_judge()
    {
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE)
        {
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }
        $sess_user = $_SESSION['user'];

        // permission が[-1]BANの場合
        if($sess_user['permission'] === '-1')
        {
            $this->session_manager->deleteSession();
            redirect(site_url('login/ban'));
        }
        // permission が[0]削除(退会)の場合
        elseif($sess_user['permission'] === '0')
        {
            $this->session_manager->deleteSession();
            redirect(site_url('login/delete'));
        }
        // permission が[2]管理者の場合
        elseif($sess_user['permission'] === '2')
        {
            redirect(site_url('admin/index'));
        }
    }

}