<?php
class Session_manager {

    //セッション情報追加メソッド
    public function addSession($user_id)
    {
        //セッション・クッキーをセット
        $_SESSION['user_id'] = array('user_id' => $user_id);
        set_cookie('user_id',$user_id);
    }

    //セッション有無判定メソッド
    public function isSession()
    {
        $cookie_id = get_cookie('user_id');
        //セッション情報(user_id)がnullだった場合FALSE
        if(!isset($_SESSION['user_id']))
        { 
            return FALSE;
        }
        //クッキー情報(user_id)がnullだった場合FALSE
        elseif(!isset($cookie_id))
        {
            return FALSE;
        }
        //セッション情報(user_id)とクッキー情報(user_id)が一致しなかった場合FALSE
        elseif($_SESSION['user_id'] !== $cookie_id)
        { 
            return FALSE;
        }
        return TRUE;
    }

    //セッション情報削除メソッド
    public function deleteSession()
    {   
        //セットされたセッション・クッキーを削除
        unset($_SESSION['user_id']);
        delete_cookie('user_id');
    }

}