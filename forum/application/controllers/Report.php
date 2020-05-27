<?php 
class Report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('users_model','threads_model','comments_model','session_manager','reports_model'));
        $this->load->library(array('session','pagination','form_validation'));
        $this->load->helper(array('form','url_helper'));
    }
    // 通報リストからコメントを削除(無視)
    public function ignore($slug = NULL)
    {
        $this->session_judge();
        if($slug === NULL )
        { 
            redirect(site_url("admin/index")); 
        }

        $post = $this->input->post('ignore_flg');
        if(!empty($post) && $post === 'TRUE')
        {
            $this->reports_model->delete_report($slug);
            redirect(site_url("admin/reports"));
        }

        $data['report_comment'] = $this->reports_model->get_report_comment($slug);

        // タイトルを渡す
        $data['title'] = "イグナイト - 通報コメント無視";
        // 通報コメント一覧画面のCSSを渡す
        $data['stylesheet'] = 'report_ignore_style.css';
        // 通報コメント一覧画面を表示する
        $this->load->view('header', $data);
        $this->load->view('report_ignore_page', $data);
        $this->load->view('footer', $data);

    }

    // 通報されたコメント投稿者をBAN
    public function ban($slug = NULL)
    {
        $this->session_judge();
        if($slug === NULL )
        { 
            redirect(site_url("admin/index")); 
        }

        $post = $this->input->post('ban_flg');
        $report_comment = $this->reports_model->get_report_comment($slug);
        if(empty($report_comment))
        {
            redirect(site_url("admin/reports"));
        }
        if(!empty($post) && $post === 'TRUE')
        {
            $this->users_model->ban_user($report_comment['commenter_id']);
            redirect(site_url("admin/reports"));
        }
        // データ取得し直し 
        $data['report_comment'] = $this->reports_model->get_report_comment($slug);

        // タイトルを渡す
        $data['title'] = "イグナイト - 通報コメント投稿者BAN";
        // 通報コメント一覧画面のCSSを渡す
        $data['stylesheet'] = 'report_ban_style.css';
        // 通報コメント一覧画面を表示する
        $this->load->view('header', $data);
        $this->load->view('report_ban_page', $data);
        $this->load->view('footer', $data);

    }
    // 通報リストからコメントを削除(無視)
    public function delete($slug = NULL)
    {
        $this->session_judge();
        if($slug === NULL )
        { 
            redirect(site_url("admin/index")); 
        }

        $post = $this->input->post('delete_flg');
        if(!empty($post) && $post === 'TRUE')
        {
            $this->comments_model->delete_comment($slug);
            $this->reports_model->delete_report($slug);
            redirect(site_url("admin/reports"));
        }

        $data['report_comment'] = $this->reports_model->get_report_comment($slug);

        // タイトルを渡す
        $data['title'] = "イグナイト - 通報コメント無視";
        // 通報コメント一覧画面のCSSを渡す
        $data['stylesheet'] = 'report_delete_style.css';
        // 通報コメント一覧画面を表示する
        $this->load->view('header', $data);
        $this->load->view('report_delete_page', $data);
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