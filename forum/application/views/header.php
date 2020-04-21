<!DOCTYPE html>
<html lang="ja">
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <!-- css -->
        <link rel="stylesheet" href="<?php echo base_url() ?>css/header_style.css" type="text/css" />
        <!-- js -->
    </head>

    <body>
        <h2>完全会員制掲示板サイト</h2>
        <h1>イグナイト</h1>
        <?php /* 
            セッションに'user'がセットされていなければ「ログイン」にLogin.php メソッドloginへのリンクを表示
            セッションに'user'がセットされていれば「○○さん」に○○さんのマイページへのリンクを表示
        */?>
        <?php if(isset($_SESSION['user'])){ 
        $user = $_SESSION['user']; ?>
        <p><a href="<?php echo site_url("forum/my_page/{$user['user_id']}") ?>"><?php echo $user['nickname'] ?></a></p>
        <?php }else{ ?>
        <p><a href="<?php echo site_url("login/login") ?>">ログイン</a></p>
        <?php } ?>
        