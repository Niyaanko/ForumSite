<?php
class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager'));
        $this->load->helper(array('form','url_helper'));
        $this->load->library(array('session','form_validation'));
    }

    public function login()
    {
        // セッション判定
        $this->session_judge();

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
                'rules' => 'required',
                'errors' => array(
                    'required' => '%s を入力していません',
                )
            )
        );
        // 検証ルールの反映
        $this->form_validation->set_rules($config);

        //既にセッションがある場合はトップページを表示する
        if($this->session_manager->isSession())
        {
            // トップページのコントローラに遷移する
            redirect(site_url('forum/index'));
        }
        // submit 前や、不正な入力のときはフォームを表示する
        elseif($this->form_validation->run() === FALSE)
        {
            // タイトルを渡す
            $data['title'] = 'イグナイト - ログイン';
            // ログイン画面のCSSを渡す
            $data['stylesheet'] = 'login_style.css';
            // ログイン画面を表示する
            $this->load->view('header', $data);
            $this->load->view('login_page',$data);
            $this->load->view('footer', $data);
        }
        // 正しく入力されたときはログイン操作を行い、トップページを表示する
        else
        {   
            $user = $this->users_model->login_user();

            // ログインに失敗した場合はフォームを表示する
            if(is_null($user))
            {
                // タイトルを渡す
                $data['title'] = 'イグナイト - ログイン';
                // エラーメッセージを渡す
                $data['error'] = 'ログインに失敗しました';
                // ログイン画面のCSSを渡す
                $data['stylesheet'] = 'login_style.css';
                // ログイン画面を表示する
                $this->load->view('header', $data);
                $this->load->view('login_page',$data);
                $this->load->view('footer', $data);
            }
            // ログインに成功した場合はセッションをセットしトップページを表示
            else
            {
                // BANされたユーザー
                if($user['permission'] === '-1')
                {
                    redirect(site_url('forum/ban'));
                }
                // 削除(退会)したユーザー
                elseif($user['permission'] === '0')
                {
                    redirect(site_url('login/delete'));
                }
                // 削除(退会)した管理者
                elseif($user['permission'] === '2')
                {
                    //セッションをセット
                    $this->session_manager->addSession($user);
                    // トップページのコントローラに遷移する
                    redirect(site_url('admin/index'));    
                }
                //セッションをセット
                $this->session_manager->addSession($user);
                // トップページのコントローラに遷移する
                redirect(site_url('forum/index'));
            }
        }
    }
    public function ban()
    {
        // タイトルを渡す
        $data['title'] = 'イグナイト - ログイン';
        // エラーメッセージを渡す
        $data['error'] = 'BANされたユーザーです';
        // ログイン画面のCSSを渡す
        $data['stylesheet'] = 'login_style.css';
        // ログイン画面を表示する
        $this->load->view('header', $data);
        $this->load->view('login_page',$data);
        $this->load->view('footer', $data);
    }
    public function delete()
    {
        // タイトルを渡す
        $data['title'] = 'イグナイト - ログイン';
        // エラーメッセージを渡す
        $data['error'] = '退会したユーザーです';
        // ログイン画面のCSSを渡す
        $data['stylesheet'] = 'login_style.css';
        // ログイン画面を表示する
        $this->load->view('header', $data);
        $this->load->view('login_page',$data);
        $this->load->view('footer', $data);
    }

    public function session_judge()
    {
        // セッションの有無を判定　なかった場合return
        if($this->session_manager->isSession() === FALSE)
        {
            return;
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
