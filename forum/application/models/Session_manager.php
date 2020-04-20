<?php
class SessionManager {

    public function __construct()
    {
        $this->load->library('session');
        $this->load->helper('cookie');
    }

    //セッション情報追加メソッド
    public function addSession($user_id)
    {
        //セッション・クッキーをセット
        $this->session = array('user_id' => $user_id);
        $this->cookie->set_cookie('user_id',$user_id);
    }

    //セッション有無判定メソッド
    public function isSession()
    {
        //セッション情報(user_id)がnullだった場合FALSE
        if(is_null($this->session['user_id']))
        { 
            return FALSE;
        }
        //クッキー情報(user_id)がnullだった場合FALSE
        elseif(is_null($this->cookie->get_cookie('user_id')))
        { 
            return FALSE;
        }
        //セッション情報(user_id)とクッキー情報(user_id)が一致しなかった場合FALSE
        elseif($this->session['user_id']) !== $this->cookie->get_cookie('user_id'))
        { 
            return FALSE;
        }
        return TRUE;
    }

    //セッション情報削除メソッド
    public function deleteSession()
    {   
        //セットされたセッション・クッキーを削除
        unset($this->session['user_id']);
        $this->cookie->delete_cookie('user_id');
    }

}