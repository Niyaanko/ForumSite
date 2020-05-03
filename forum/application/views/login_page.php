<main>

    <?php echo form_open("login/login"); ?>
        <h2 class="form_title">ログイン</h2>
        <h3 class="login_mailaddress">メールアドレス<input type="text" name="mailaddress" value="<?php echo set_value('mailaddress'); ?>"placeholder="メールアドレスを入力" required></h3>
        <div class="error_mail"><?php echo form_error('mailaddress'); ?></div>
        <h3 class="login_password">パスワード<input type="password" name="password" placeholder="パスワードを入力" required></h3>
        <p class="error_pw"><?php echo form_error('password'); ?></p>
        <p class="error"><?php if(isset($error)){ echo $error; }?></p>
        <input class="login_button" type="submit" name="submit" value="ログイン" >
    </form>
    <p class="regist_link">アカウントをお持ちでない方は<a href=<?php echo site_url("register/regist"); ?>>こちら</a></p>
</main>
