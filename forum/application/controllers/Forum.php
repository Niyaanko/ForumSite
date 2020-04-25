<?php
class Forum extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager','threads_model','comments_model'));
        $this->load->library('session','pagination');
        $this->load->helper('url_helper');
    }

    // 全スレッド情報を取得し、スレッド一覧のページを表示
    public function index()
    {
        // $order で並び替え方法を指定 NULLの場合はスレッド作成日(降順)
        // セッションの有無を判定　なかった場合ログインページへ
        if($this->session_manager->isSession() === FALSE){
            $this->session_manager->deleteSession();
            redirect(site_url('login/login'));
        }
        // 全スレッド情報を取得
        $threads = $this->threads_model->get_threads(); 
        // $threadsがNULLの場合
        if(empty($threads))
        {
            $data['msg'] = 'スレッドが存在しません';
        }
        // $threadsがNULLでない場合
        else
        {
            // スレッド作成日で降順にソート
            array_multisort($sort, SORT_DESC, $threads);

            // Forum コントローラのベースURLを定義
            $config['base_url'] = base_url().'forum/index/';

            // 合計データ数を定義
            $config['total_rows'] = count($threads);

            // 1ページに表示するスレッドの数を定義
            $config['per_page'] = 10;

            // 選択中のページ番号の前後に表示したい "数字" リンクの数を定義
            // たとえば、3を指定すると7ページ目を表示しているとき < 4 5 6 7 8 9 10 > となる
            $config["num_links"] = 3;

            // configを反映
            $this->pagination->initialize($config);
            
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $data["links"] = $this->pagination->create_links();
            $threads = $this->students_model->get_threads_limit($config["per_page"], $page);

            // それぞれのスレッドのコメント情報取得
            for($i = 0;$i < count($threads);$i++)
            {
                $comments = $this->comments_model->get_count($thread['thread_id']); 
                // キー comment_count でコメント数を追加
                $threads[$i] = array_merge($threads[$i],array('comment_count' => $comments));
            }
            // スレッドデータのセット
            $data['threads'] = $threads;
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