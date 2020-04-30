<main>
    <?php echo validation_errors(); ?>
    <?php if(isset($error)){ echo $error; }?>

    <?php echo form_open('login/login'); ?>
        <h2 class="form_title">ログイン</h2>
        <h3 class="login_mailaddress">メールアドレス<input type="text" name="mailaddress" value="<?php echo set_value('mailaddress'); ?>"/></h3>
        <h3 class="login_password">パスワード<input type="password" name="password"/></h3>
        <input class="login_button" type="submit" name="submit" value="ログイン" />
    </form>
    <p class="regist_link">アカウントをお持ちでない方は<a href=<?php echo site_url("register/regist"); ?>>こちら</a></p>
</main>
