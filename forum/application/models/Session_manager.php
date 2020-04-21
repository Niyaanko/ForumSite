<?php
class Session_manager {

    //セッション情報追加メソッド
    public function addSession($user_id)
    {
        //セッションをセット
        $_SESSION['user_id'] = $user_id;
    }

    //セッション有無判定メソッド
    public function isSession()
    {
        //セッション情報(user_id)がnullだった場合FALSE
        if(!isset($_SESSION['user_id']))
        { 
            return FALSE;
        }
        return TRUE;
    }

    //セッション情報削除メソッド
    public function deleteSession()
    {   
        //セットされたセッションを削除
        unset($_SESSION['user_id']);
    }

}