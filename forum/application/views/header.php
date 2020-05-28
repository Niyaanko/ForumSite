<!DOCTYPE html>
<html lang="ja">
    <head>
        <title><?= $title; ?></title>
        <meta charset="UTF-8">
        <!-- css読込 -->
            <!-- header -->
        <link rel="stylesheet" href="<?= base_url(); ?>css/header_style.css" type="text/css" />
            <!-- footer -->
        <link rel="stylesheet" href="<?= base_url(); ?>css/footer_style.css" type="text/css" />
            <!--  body  -->
<?php if(isset($stylesheet)): ?>
        <link rel="stylesheet" href="<?= base_url(); ?>css/<?= $stylesheet; ?>" type="text/css" />
<?php endif; ?>
            <!-- FontAwesome -->
            <link rel="stylesheet" href="<?= base_url(); ?>/font-awesome/css/all.css" />
        <!-- javascript読込 -->
<?php if(isset($js)): ?>
        <script type="text/javascript" src="<?= base_url(); ?>js/<?= $js; ?>"></script>
<?php endif; ?>
    </head>
    <body>
        <!-- ヘッダー -->
        <header>
<?php       
    $top_url = site_url("register/regist");
    $sess_user = NULL;
    if(isset($_SESSION['user'])):
        $sess_user = $_SESSION['user'];
    endif;    
    if(!empty($sess_user) && $sess_user['permission'] === 'NORMAL'):
        $top_url = site_url("forum/index");
    elseif(!empty($sess_user) && $sess_user['permission'] === 'ADMIN'):
        $top_url = site_url("admin/index");
    endif;
?>
            <h2 class="header_subtitle">
                <a href="<?= $top_url ?>">完全会員制掲示板</a>
            </h2>
            <h1 class="header_title">
                <a href="<?= $top_url ?>">イグナイト</a>
            </h1>
<?php 
    if(!empty($sess_user) && $sess_user['permission'] === 'NORMAL'):?>
            <p>
                <a class="header_mypage" href="<?php echo site_url("mypage/mypage") ?>"><?= html_escape($sess_user['nickname']) ?><span>さん</span></a>
            </p>
<?php
    elseif(!empty($sess_user) && $sess_user['permission'] === 'ADMIN'):?> 
            <p class="header_admin">管理者:<?= html_escape($sess_user['nickname']) ?><span> でログイン中</span></p>
            <div class="parent_logout_link">
                <a class="logout_link" href="<?php echo site_url("admin/logout") ?>">ログアウト</a>
            </div>
<?php
    else:?>
            <div class="header_login_btn"><a href="<?php echo site_url("login/login") ?>">ログイン</a></div>
<?php 
    endif;?>
        </header>
        <!-- ヘッダー -->