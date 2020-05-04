<?php 
class Register extends CI_Controller {

    // コンストラクタ
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager'));
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('form','url_helper'));
    }

    // アカウント登録操作を行う
    public function regist()
    {
        // 検証ルールの複数指定
        $config = array(
            array(
                'field' => 'mailaddress',
                'label' => 'メールアドレス',
                'rules' => 'required|max_length[90]|is_unique[users.mailaddress]|valid_email',
                'errors' => array(
                    'required' => '%s を入力していません',
                    'max_length' => '%s は90文字以内で入力して下さい',
                    'is_unique' => '%s は既に使用されています',
                    'valid_email' => 'メールアドレスを入力して下さい'
                )
            ),
            array(
                'field' => 'password',
                'label' => 'パスワード',
                'rules' => 'required|min_length[8]|max_length[12]',
                'errors' => array(
                    'required' => '%s を入力していません',
                    'min_length' => '%s は8文字以上で入力して下さい',
                    'max_length' => '%s は12文字以内で入力して下さい'
                )
            )
        );

        $this->form_validation->set_rules($config);
        
        //既にセッションがある場合はトップページを表示する
        if($this->session_manager->isSession())
        {
            // トップページのコントローラに遷移する
            redirect(site_url('forum/index'));
        }
        // submit 前や、不正な入力のときはフォームを表示する
        elseif($this->form_validation->run() === FALSE )
        {
            // タイトルを渡す
            $data['title'] = 'イグナイト - アカウント登録';
            // ログイン画面のCSSを渡す
            $data['stylesheet'] = 'register_style.css';
            // アカウント登録画面を表示する
            $this->load->view('header', $data);
            $this->load->view('register_page');
            $this->load->view('footer', $data);
        }
        // 正しく入力されたときはアカウント登録操作を行い、トップページを表示する
        else
        {   
            // アカウントのDB登録
            $this->users_model->regist_user();
            //登録したアカウントのid取得
            $user = $this->users_model->login_user();
            // セッション・クッキーを登録する
            $this->session_manager->addSession($user);
            // トップページを表示する
            redirect(site_url('forum/index'));
        }
    }
}
