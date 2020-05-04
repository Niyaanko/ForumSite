<?php
class Session_manager {

    // セッション情報追加メソッド
    public function addSession($user)
    {
        // セッションをセット

        $_SESSION['user'] = $user;
    }

    // セッション有無判定メソッド
    public function isSession()
    {
        // セッション情報(user_id)がnullだった場合FALSE
        if(!isset($_SESSION['user']))
        { 
            return FALSE;
        }
        return TRUE;
    }

    // セッション情報削除メソッド
    public function deleteSession()
    {   
        // セットされたセッションを削除
        unset($_SESSION['user']);
        unset($_SESSION['sort']);
        unset($_SESSION['search']);
    }

    // トップページの並び替え情報追加メソッド
    public function addSortSession($sort){
        // セッションをセット
        $_SESSION['sort'] = $sort;
    }

    public function isSortSession(){
        // セッション情報(user_id)がnullだった場合FALSE
        if(!isset($_SESSION['sort']))
        { 
            return FALSE;
        }
        return TRUE;
    }

    // トップページの並び替え情報追加メソッド
    public function addSearchSession($search){
        // セッションをセット
        $_SESSION['search'] = $search;
    }


    public function isSearchSession(){
        // セッション情報(user_id)がnullだった場合FALSE
        if(!isset($_SESSION['search']))
        { 
            return FALSE;
        }
        return TRUE;
    }
    // 
    public function deleteSearchSession(){
        
        unset($_SESSION['search']);
    }

}