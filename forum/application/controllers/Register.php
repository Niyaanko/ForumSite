<?php 
class Register extends CI_Controller {

    // コンストラクタ
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager'));
        $this->load->library(array('session','form_validation'));
        $this->load->helper(array('form','url_helper','cookie','url'));
    }

    // アカウント登録操作を行う
    public function regist()
    {
        // タイトルのセット
        $data['title'] = 'イグナイト - アカウント登録';

        //検証ルールの複数指定
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
                'rules' => 'required|max_length[12]',
                'errors' => array(
                    'required' => '%s を入力していません',
                    'max_length' => '%s は12文字以内で入力して下さい'
                )
            )
        );

        $this->form_validation->set_rules($config);
        
        //既にセッションがある場合はトップページを表示する
        if($this->session_managaer->isSession())
        {
            // トップページのコントローラに遷移する
            $this->url->redirect(site_url("forum/view"));
        }
        // submit 前や、不正な入力のときはフォームを表示する
        elseif($this->form_validation->run() === FALSE )
        {
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
            $user_id = $this->users_model->login_user();
            // セッション・クッキーを登録する
            $this->session_managaer->addSession($user_id);
            // トップページを表示する
            $this->url->redirect(site_url("top/view"));
        }
    }
}
