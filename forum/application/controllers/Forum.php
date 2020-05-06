<?php
class Forum extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','session_manager','threads_model','comments_model','reports_model'));
        $this->load->library(array('session','pagination','form_validation'));
        $this->load->helper('url_helper');
    }

    // 全スレッド情報を取得し、スレッド一覧のページを表示
    public function index()
    {
        // セッション判定
        $this->session_judge();

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
        // セッション判定
        $this->session_judge();

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
        
        $user = $_SESSION['user'];

        // 正しく入力されたときのみDBにコメント追加
        if($this->form_validation->run() === TRUE)
        {
            
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

        // 現在のページ位置取得
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['page_data'] = $page;
        // ページリンクの生成
        $data["links"] = $this->pagination->create_links();

        // 範囲を指定してコメントを取得
        $comments = $this->comments_model->get_comments_limit($config["per_page"], $page, $thread['thread_id'], $user['user_id']);

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

    // コメント通報
    public function report($slug = FALSE)
    {
        // セッション判定
        $this->session_judge();

        // コメントが指定されていない場合トップページへ
        if($slug === FALSE)
        {
            redirect(site_url('forum/index'));
        }

        // 指定されたコメントが存在しない場合トップページへ
        $report_comment = $this->comments_model->get_comment($slug);
        if(empty($report_comment))
        {
            redirect(site_url('forum/index'));
        }

        // 通報が確定されている場合
        if(!empty($this->input->post('report_comment_id')))
        {
            $user = $_SESSION['user'];
            $this->reports_model->report_comment($slug,$user['user_id']);
            $data['msg'] = '通報が完了しました';

        }
        // 通報が確定されていない場合
        else
        {
            // スレッド情報をセット
            $thread = $this->threads_model->get_threads($report_comment['thread_id']);
            $data['report_comment_thread'] = $thread;
            $data['report_comment_user'] = $this->users_model->get_user($report_comment['commenter_id']);
        }
        // コメント情報をセット
        $data['report_comment'] = $report_comment;
        
        // タイトルを渡す
        $data['title'] = "イグナイト - コメント通報";
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'report_style.css';

        // スレッドページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('report_page', $data);
        $this->load->view('footer', $data);
    }
    // 検索クリア
    public function clear()
    {
        $this->session_manager->deleteSearchSession();
        redirect(site_url('forum/index'));
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