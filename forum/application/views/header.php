<!DOCTYPE html>
<html lang="ja">
    <head>
        <title><?php echo $title; ?></title>
        <meta charset="UTF-8">
        <!-- css -->
            <!-- header -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/header_style.css" type="text/css" />
            <!-- footer -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/footer_style.css" type="text/css" />
            <!--  body  -->
        <?php if(isset($stylesheet)){ ?>
        <link rel="stylesheet" href="<?php echo base_url() ?>css/<?php echo $stylesheet ?>" type="text/css" />
        <?php } ?>
        <!-- js -->
        <?php if(isset($js)){ ?>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/<?php echo $js; ?>"></script>
        <?php } ?>
    </head>

    <body>
        <header>
            <?php // セッションに'user'がセットされていなければタイトルにRegister/Registへのリンクを表示
                  // セッションに'user'がセットされていればタイトルにForum/indexへのリンクを表示
            $top_url = isset($_SESSION['user']) ? site_url("forum/index") : site_url("register/regist") ?>
            <h2 class="header_subtitle"><a href="<?php echo $top_url ?>">完全会員制掲示板</a></h2>
            <h1 class="header_title"><a href="<?php echo $top_url ?>">イグナイト</a></h1>
            <?php 
            /* セッションに'user'がセットされていなければ「ログイン」にLogin.php メソッドloginへのリンクを表示
               セッションに'user'がセットされていれば「○○さん」に○○さんのマイページへのリンクを表示 */ 
            if(isset($_SESSION['user'])){ 
            $user = $_SESSION['user']; 
                if($user['permission'] === '1'){?>
            <p>
                <a class="header_mypage" href="<?php echo site_url("mypage/mypage") ?>"><?php echo html_escape($user['nickname']) ?><span>さん</span></a>
            </p>
            <?php
                }elseif($user['permission'] === '2'){?> 
            <p class="header_admin">管理者:<?php echo html_escape($user['nickname']) ?><span> でログイン中</span></p>
            <div class="parent_logout_link">
                <a class="logout_link" href="<?php echo site_url("admin/logout") ?>">ログアウト</a>
            </div>
            <?php
                } 
            }else{ ?>
            <p><a class="header_login" href="<?php echo site_url("login/login") ?>">ログイン</a></p>
            <?php 
            } ?>
        </header>
        