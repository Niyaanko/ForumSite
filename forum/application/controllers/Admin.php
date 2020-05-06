<?php 
class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','threads_model','session_manager','reports_model'));
        $this->load->library(array('session','pagination','form_validation'));
        $this->load->helper(array('form','url_helper'));
    }

    public function index()
    {
        // セッション判定
        $this->session_judge();

        // タイトルを渡す
        $data['title'] = "イグナイト - 管理者トップ";
        // トップページ画面のCSSを渡す
        $data['stylesheet'] = 'admin_style.css';

        // スレッドページ画面を表示する
        $this->load->view('header', $data);
        $this->load->view('admin_page', $data);
        $this->load->view('footer', $data);

    }

    public function reports()
    {
        // セッション判定
        $this->session_judge();
        // 通報されたコメントの総数を取得
        $report_total_count = $this->reports_model->get_report_count();
        // 1ページに表示するコメントの数
        $per_page = 20;
        // 現在のページ数取得
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($report_total_count === '0'){
            $data['msg'] = '通報されたコメントはありません';
        }else{
            // 通報コメント一覧取得(LIMIT > $per_page, OFFSET > $page)
            $data['report_comments'] = $this->reports_model->get_report_comments($per_page, $page, 'report_count','DESC');
            $config['base_url'] = base_url().'admin/reports/';
            $config['total_rows'] = $report_total_count;
            $config['per_page'] = $per_page;
            $config['attributes'] = array('class' => 'page_link');
            $config['num_links'] = 3;
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
        }
        // タイトルを渡す
        $data['title'] = "イグナイト - 通報コメント一覧";
        // 通報コメント一覧画面のCSSを渡す
        $data['stylesheet'] = 'report_list_style.css';
        // 通報コメント一覧画面を表示する
        $this->load->view('header', $data);
        $this->load->view('report_list_page', $data);
        $this->load->view('footer', $data);
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
        elseif($sess_user['permission'] === '1')
        {
            redirect(site_url('forum/index'));
        }
    }
}