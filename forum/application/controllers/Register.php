<?php 
class Register extends CI_Controller {

    // コンストラクタ
    public function __construct()
    {
        parent::__construct();

        $this->load->model('register_model');

        $this->load->helper('url_helper');
    }

    // アカウント登録画面の表示を行う
    public function view()
    {

    }

    // アカウント登録操作を行う
    public function regist()
    {
        // フォームヘルパーのロード
        $this->load->helper('form');

        // フォームバリデーションライブラリのロード
        $this->load->library('form_validation');

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

        // 重複するメールアドレスがいないかチェック

        // パスワードが正しく入力されているかチェック
        
        //既にセッションがある場合はトップページを表示する
        $isSession = false
        if($isSession)
        {

            // トップページを表示する
            $this->load->view('header', $data);
            $this->load->view('');
            $this->load->view('footer', $data);
        } 
        // submit 前や、不正な入力のときはフォームを表示する。
        elseif($this->form_validation->run() === FALSE )
        {
            
            // アカウント登録画面を表示する
            $this->load->view('header', $data);
            $this->load->view('');
            $this->load->view('footer', $data);
        }
        // 正しく入力されたときはアカウント登録操作を行い、トップページを表示する
        else
        {
            
            // 
            $this->news_model->();
            // セッション・クッキーを登録する

            // トップページを表示する
            $this->load->view('header', $data);
            $this->load->view('');
            $this->load->view('footer', $data);
        }
    }
}
