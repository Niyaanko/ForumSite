<main>
    <a class="toplink_top" href="<?= site_url("admin/index");?>">トップページへ</a>
    <h2 class="main_title">管理者アカウント作成</h2>
    <?php 
    if(isset($msg)):?>
    <h3 class="done_msg"><?= $msg; ?></h3>
    <a class="create_admin_link" href="<?= site_url("admin/create_admin");?>">続けて管理者アカウントを作成する</a>
    <?php 
    else:?>
    <?= form_open("admin/create_admin"); ?>
        <h3 class="regist_nickname">ニックネーム<input type="text" name="nickname" value="<?= html_escape(set_value('nickname')); ?>" placeholder="ニックネームを入力" maxlength="10" required></h3>
        <div class="error_nickname"><?= form_error('nickname'); ?></div>
        <h3 class="regist_mailaddress">メールアドレス<input type="text" name="mailaddress" value="<?= html_escape(set_value('mailaddress')); ?>" placeholder="メールアドレスを入力" maxlength="90" required></h3>
        <div class="error_mail"><?= form_error('mailaddress'); ?></div>
        <h3 class="regist_password">パスワード<input type="password" name="password" maxlength="12" placeholder="8文字以上12文字以内で入力" required></h3>
        <div class="error_pw"><?= form_error('password'); ?></div>
        <input class="regist_button" type="submit" name="submit" value="登録">
    </form>
    <?php 
    endif;?>
</main>
