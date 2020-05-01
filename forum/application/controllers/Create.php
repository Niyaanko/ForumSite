<?php 
class Create extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','threads_model','session_manager'));
        $this->load->library(array('session','form_validation'));
        $this->load->helper('url_helper');
    }

    // スレッド作成ページ
    public function create_thread()
    {
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE){
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }

        // 検証ルールの指定
        $config = 
            array(
                'field' => 'title',
                'label' => 'タイトル',
                'rules' => 'required|max_length[20]',
                'errors' => 
                array(
                    'required' => '%s を入力していません',
                    'max_length' => '%s は20文字以内で入力して下さい',
                )
            );

        // 検証ルールのセット
        $this->form_validation->set_rules($config);

        // submit 前や、不正な入力のときはフォームを表示する
        if($this->form_validation->run() === FALSE)
        {
            // タイトルを渡す
            $data['title'] = 'イグナイト - スレッド作成';
            // スレッド作成ページのCSSを渡す
            $data['stylesheet'] = 'create_thread_style.css';
            // スレッド作成ページを表示する
            $this->load->view('header', $data);
            $this->load->view('create_thread_page', $data);
            $this->load->view('footer', $data);

        // 正しく入力された場合、INSERT文を実行し作成したスレッドのページを表示する
        }else{
            $user = $_SESSION['user'];
            // スレッドの作成、スレッドIDの取得
            $thread_id = $this->threads_model->create_thread($user['user_id']);
            // 作成したスレッドのページを表示する
            redirect(site_url('forum/view/'.$thread_id));
        }
        
    }

}