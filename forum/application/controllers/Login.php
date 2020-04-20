<?php
class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->helper('cookie');
    }

    public function login()
    {
        // フォームヘルパーのロード
        $this->load->helper('form');
        // フォームバリデーションライブラリのロード
        $this->load->library('form_validation');
        // タイトルのセット
        $data['title'] = 'イグナイト - ログイン';
        //検証ルールの複数指定
        $config = array(
            array(
              'field' => 'mailaddress',
              'label' => 'メールアドレス',
              'rules' => 'required|max_length[90]|valid_email',
              'errors' => array(
                  'required' => '%s を入力していません',
                  'max_length' => '%s は90文字以内で入力して下さい',
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
        if((!is_null($this->session['user_id']) && !is_null($this->cookie->get_cookie('user_id')))
            && $this->session['user_id'] === $this->cookie->get_cookie('user_id')){
            // トップページを表示する
            $this->load->view('header', $data);
            $this->load->view('top_page');
            $this->load->view('footer', $data);
        }
        // submit 前や、不正な入力のときはフォームを表示する
        elseif($this->form_validation->run() === FALSE)
        {
            // アカウント登録画面を表示する
            $this->load->view('header', $data);
            $this->load->view('login_page',$data);
            $this->load->view('footer', $data);
        }
        // 正しく入力されたときはログイン操作を行い、トップページを表示する
        else
        {   
            $user_id = $this->users_model->login_user();
            // ログインに失敗した場合はフォームを表示する
            if(is_null($user_id))
            {
                // アカウント登録画面を表示する
                $this->load->view('header', $data);
                $this->load->view('login_page',$data);
                $this->load->view('footer', $data);
            }
            // ログインに成功した場合はセッション・クッキーをセットしトップページを表示
            else
            {
                //セッション・クッキーをセット
                $this->session = array('user_id' => $user_id);
                $this->cookie->set_cookie('user_id',$user_id);
                // トップページを表示する
                $this->load->view('header', $data);
                $this->load->view('top_page');
                $this->load->view('footer', $data);
            }
        }
    }
}
