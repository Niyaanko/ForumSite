<!DOCTYPE html>
<html lang="ja">
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <!-- css -->
            <!-- header -->
        <link rel="stylesheet" href="<?php echo base_url() ?>css/header_style.css" type="text/css" />
            <!-- footer -->
        <link rel="stylesheet" href="<?php echo base_url() ?>css/footer_style.css" type="text/css" />
            <!--  body  -->
        <?php if(isset($stylesheet)){ ?>
        <link rel="stylesheet" href="<?php echo base_url() ?>css/<?php echo $stylesheet ?>" type="text/css" />
        <?php } ?>
        <!-- js -->
    </head>

    <body>
        <header>
            <?php // セッションに'user'がセットされていなければタイトルにRegister/Registへのリンクを表示
                  // セッションに'user'がセットされていればタイトルにForum/indexへのリンクを表示
            $top_url = isset($_SESSION['user']) ? site_url("forum/index") : site_url("register/regist") ?>
            <h2 class="header_subtitle"><a href="<?php echo $top_url ?>">完全会員制掲示板</a></h2>
            <h1 class="header_title"><a href="<?php echo $top_url ?>">イグナイト</a></h1>
            <?php /* 
            セッションに'user'がセットされていなければ「ログイン」にLogin.php メソッドloginへのリンクを表示
            セッションに'user'がセットされていれば「○○さん」に○○さんのマイページへのリンクを表示
            */?>
            <?php if(isset($_SESSION['user'])){ 
            $user = $_SESSION['user']; ?>
            <p><a class="header_mypage" href="<?php echo site_url("forum/my_page/{$user['user_id']}") ?>"><?php echo $user['nickname'] ?>さん</a></p>
            <?php }else{ ?>
            <p><a class="header_login" href="<?php echo site_url("login/login") ?>">ログイン</a></p>
            <?php } ?>
        </header>
        