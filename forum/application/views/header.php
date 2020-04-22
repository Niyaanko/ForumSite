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
            <h2 class="header_subtitle">完全会員制掲示板</h2>
            <h1 class="header_title">イグナイト</h1>
            <?php /* 
            セッションに'user'がセットされていなければ「ログイン」にLogin.php メソッドloginへのリンクを表示
            セッションに'user'がセットされていれば「○○さん」に○○さんのマイページへのリンクを表示
            */?>
            <?php if(isset($_SESSION['user'])){ 
            $user = $_SESSION['user']; ?>
            <p><a class="header_mypage" href="<?php echo site_url("forum/my_page/{$user['user_id']}") ?>"><?php echo $user['nickname'] ?></a></p>
            <?php }else{ ?>
            <p><a class="header_login" href="<?php echo site_url("login/login") ?>">ログイン</a></p>
            <?php } ?>
        </header>
        