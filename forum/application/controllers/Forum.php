<?php
class Forum extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager','threads_model','comments_model'));
        $this->load->library('session');
        $this->load->helper('url_helper');
    }

    // 全スレッド情報を取得し、スレッド一覧のページを表示
    public function index()
    {
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE){
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }

        // 全スレッド情報を取得
        $threads = $this->threads_model->get_threads(); 

        // それぞれのスレッドのコメント情報取得
        if($threads !== NULL){
            for($i = 0;$i < count($threads);$i++){
                $comments = $this->comments_model->get_comments($thread['thread_id']); 
                // キー comment_count でコメント数を追加
                $threads[$i] = array_merge($threads[$i],array('comment_count' => count($comments)));
            }
            $data['threads'] = $threads;
        }else{
            $data['msg'] = 'スレッドがありません';
        }

        // タイトルを渡す
        $data['title'] = 'イグナイト - トップページ';
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'top_style.css';
        // トップページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('top_page', $data);
        $this->load->view('footer', $data);

    }

    // 指定されたスレッドの情報を取得し、スレッドのページを表示
    public function view($slug = NULL)
    {
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE){
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }
        
        // 指定したスレッドの情報を取得
        $data['thread_item'] = $this->threads_model->get_threads($slug); 
        
        // 指定されたスレッドが存在しなければ404表示
        if(empty($data['thread_item']))
        {
            show_404();
        }

        // 指定されたスレッドの全コメント情報を取得
        $data['comments'] = $this->comments_model->get_comments($slug);

        // タイトルを渡す
        $data['title'] = "イグナイト - {$data['thread_item']['title']}";
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'thread_style.css';

        // スレッドページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('thread_page', $data);
        $this->load->view('footer', $data);

    }

    public function logout()
    {
        $this->session_manager->deleteSession();
        redirect(site_url('login/login'));
    }

}