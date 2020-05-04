<?php
class Forum extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager','threads_model','comments_model'));
        $this->load->library(array('session','pagination','form_validation'));
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

        // $threadsがNULLの場合
        if(empty($threads))
        {
            $data['msg'] = 'まだスレッドがありません';
        }
        // $threadsがNULLでない場合
        else
        {
            // 1ページに表示するスレッドの数
            $per_page = 10;

            // 現在のページ数取得
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            // [スレッドを検索]フォームから検索ワード取得
            $search_val = $this->input->post('search');
            if(!empty($search_val))
            {
                $this->session_manager->addSearchSession($search_val);
            }
            elseif($this->session_manager->isSearchSession())
            {
                $search_val = $_SESSION['search'];
            }
            else
            {
                $search_val = NULL;
            }

            // 検索ワードがない場合は全スレッド、
            // 検索ワードがある場合検索にヒットしたスレッドの数を取得
            $thread_count = $this->threads_model->get_thread_count($search_val);

            // 初期値
            $sort = 'creation_datetime-DESC';
            // フォームからプルダウンの値を受け取った場合並び替え情報取得
            if(!empty($this->input->post('sorts')))
            {
                // フォームから並び替え情報を取得
                $sort = $this->input->post('sorts');
            }
            elseif($this->session_manager->isSortSession())
            {
                // セッションから並び替え情報を取得
                $sort = $_SESSION['sort'];
            }
            // 並び替え情報をセッションにセット
            $this->session_manager->addSortSession($sort);
            // - で分割 [0]に並び替えのキー,[1]にDESC or ASCが格納
            $pulldown_arr = explode("-", $sort);

            // 条件を指定してスレッドを取得
            $threads = $this->threads_model->get_threads_range(
                $per_page, $page, $pulldown_arr[0], $pulldown_arr[1],$search_val);

            $data['threads'] = $threads;

            // ベースURLを定義
            $config['base_url'] = base_url().'forum/index/';

            // 合計データ数を定義
            $config['total_rows'] = $thread_count;

            // 1ページに表示するスレッドの数を定義
            $config['per_page'] = $per_page;

            // ページネーションで生成されたリンク クラス属性の追加 "page_link" 
            $config['attributes'] = array('class' => 'page_link');
            
            // 選択中のページ番号の前後に表示したい "数字" リンクの数を定義
            // たとえば、3を指定すると7ページ目を表示しているとき < 4 5 6 7 8 9 10 > となる
            $config['num_links'] = 3;

            // 最初のページへのリンクと最後のページへのリンクを非表示
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;

            // configを反映
            $this->pagination->initialize($config);

            $data['links'] = $this->pagination->create_links();
        }

        // タイトルを渡す
        $data['title'] = 'イグナイト - トップ';
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
        // スレッドの情報が指定されていない場合トップページを表示する
        if($slug === NULL)
        {
            redirect(site_url('forum/index'));
        }
        // 指定したスレッドの情報を取得
        $thread = $this->threads_model->get_threads($slug); 
        
        // 指定されたスレッドが存在しなければ404表示
        if(empty($thread))
        {
            show_404();
        }

        // スレッド作成者の取得
        $creator = $this->users_model->get_user($thread['creator_id']);
        // NULLでなければニックネームをセット
        if(!empty($creator)){
            $thread['creator_nickname'] = $creator['nickname'];
        }
            
        // 検証ルールのセット
        $this->form_validation->set_rules(
            'comment','コメントテキスト',
            'required|max_length[100]',
            array(
                'required' => '%s を入力していません',
                'max_length' => '%s は100文字以内で入力して下さい',
            )
        );
        
        // 正しく入力されたときのみDBにコメント追加
        if($this->form_validation->run() === TRUE)
        {
            $user = $_SESSION['user'];
            $this->comments_model->add_comments($user['user_id'], $thread['thread_id']);
        }

        // ベースURLを定義
        $config['base_url'] = base_url().'forum/view/'.$thread['thread_id'].'/';

        // 合計コメント数を定義
        $config['total_rows'] = $this->comments_model->get_thread_count($thread['thread_id']);

        // 1ページに表示するコメントの数を定義
        $config['per_page'] = 100;

        // ページネーションで生成されたリンク クラス属性の追加 "page_link" 
        $config['attributes'] = array('class' => 'page_link');

        // 選択中のページ番号の前後に表示したい "数字" リンクの数を定義
        // たとえば、3を指定すると7ページ目を表示しているとき < 4 5 6 7 8 9 10 > となる
        $config["num_links"] = 3;

        // 最初のページへのリンクと最後のページへのリンクを非表示
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;

        // configを反映
        $this->pagination->initialize($config);

        // ページリンクの生成
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();

        // 範囲を指定してコメントを取得
        $comments = $this->comments_model->get_comments_limit($config["per_page"], $page, $thread['thread_id']);

        // それぞれのコメントのユーザー名取得
        for($i = 0;$i < count($comments);$i++)
        {
            // ユーザー取得
            $commentor = $this->users_model->get_user($comments[$i]['commenter_id']); 
            // キー comment_count でコメント数を追加
            $comments[$i] = array_merge($comments[$i],array('nickname' => $commentor['nickname']));
        }
        // スレッドデータのセット
        $data['comments'] = $comments;
        $data['thread'] = $thread;
        
        // タイトルを渡す
        $data['title'] = "イグナイト - {$thread['title']}";
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'thread_style.css';

        // コメント投稿後に更新したとき、フォームが再送信されるのを防ぐ
        if($this->input->post('comment')){
            redirect(site_url('forum/view/'.$thread['thread_id']));
        }
        // スレッドページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('thread_page', $data);
        $this->load->view('footer', $data);

    }
    // 検索クリア
    public function clear()
    {
        $this->session_manager->deleteSearchSession();
        redirect(site_url('forum/index'));
    }

}