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

    public function logout()
    {
        $this->session_manager->deleteSession();
        redirect(site_url('login/login'));
    }

    public function reports()
    {
        // セッション判定
        $this->session_judge();
        // 通報されたコメントの総数を取得
        $report_total_count = $this->reports_model->get_report_count();
        // 1ページに表示するコメントの数
        $per_page = 10;
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

    // ユーザー一覧を表示
    public function users()
    {
        // セッション判定
        $this->session_judge();
        // ボタンが押されていた場合
        $ban_user_id = $this->input->post('ban_hdn');
        $recover_user_id = $this->input->post('recover_hdn');
        // BANボタンが押されていた場合BAN
        // 回復ボタンが押されていた場合回復
        if(!empty($ban_user_id)){
            $this->users_model->ban_user($ban_user_id);
            redirect(site_url("admin/users"));
        }elseif(!empty($recover_user_id)){
            $this->users_model->recover_user($recover_user_id);
            redirect(site_url("admin/users"));
        }

        // 通報されたコメントの総数を取得
        $user_total_count = $this->users_model->get_user_count();
        // 1ページに表示するユーザーの数
        $per_page = 10;
        // 現在のページ数取得
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        if($user_total_count === '0'){
            $data['msg'] = 'まだユーザーはいません';
        }else{
            // 通報コメント一覧取得(LIMIT > $per_page, OFFSET > $page)
            $data['users'] = $this->users_model->get_users_info($per_page, $page, 'report_count','DESC');
            $config['base_url'] = base_url().'admin/users/';
            $config['total_rows'] = $user_total_count;
            $config['per_page'] = $per_page;
            $config['attributes'] = array('class' => 'page_link');
            $config['num_links'] = 3;
            $config['first_link'] = FALSE;
            $config['last_link'] = FALSE;
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
        }
        // タイトルを渡す
        $data['title'] = "イグナイト - ユーザー一覧";
        // 通報コメント一覧画面のCSSを渡す
        $data['stylesheet'] = 'user_list_style.css';
        // 通報コメント一覧画面を表示する
        $this->load->view('header', $data);
        $this->load->view('user_list_page', $data);
        $this->load->view('footer', $data);
    }

    // 管理者アカウント作成
    public function create_admin()
    {
        $this->session_judge();
        // 検証ルールの複数指定
        $config = array(
            array(
                'field' => 'nickname',
                'label' => 'ニックネーム',
                'rules' => 'required|max_length[10]',
                'errors' => array(
                    'required' => '%s を入力していません',
                    'max_length' => '%s は10文字以内で入力して下さい'
                )
            ),
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
                'rules' => 'required|min_length[8]|max_length[12]',
                'errors' => array(
                    'required' => '%s を入力していません',
                    'min_length' => '%s は8文字以上で入力して下さい',
                    'max_length' => '%s は12文字以内で入力して下さい'
                )
            )
        );
        $this->form_validation->set_rules($config);
        // タイトルを渡す
        $data['title'] = "イグナイト - 管理者アカウント作成";
        // 通報コメント一覧画面のCSSを渡す
        $data['stylesheet'] = 'create_admin_style.css';
        if($this->form_validation->run())
        {
            $this->users_model->regist_admin();
            $data['msg'] = '管理者アカウント作成に成功しました';
        }
        // 通報コメント一覧画面を表示する
        $this->load->view('header', $data);
        $this->load->view('create_admin_page', $data);
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
        if($sess_user['permission'] === 'BANNED')
        {
            $this->session_manager->deleteSession();
            redirect(site_url('login/ban'));
        }
        // permission が[0]削除(退会)の場合
        elseif($sess_user['permission'] === 'DELETED')
        {
            $this->session_manager->deleteSession();
            redirect(site_url('login/delete'));
        }
        // permission が[1]userの場合
        elseif($sess_user['permission'] === 'NORMAL')
        {
            redirect(site_url('forum/index'));
        }
    }
}