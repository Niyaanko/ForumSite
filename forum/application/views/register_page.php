<main>
    <h2 class="catchcopy">イグナイトに登録して掲示板を利用しましょう！</h2>

    
    

    <?php echo form_open("register/regist"); ?>
        <h2 class="form_title">アカウント登録</h2>
        <h3 class="regist_mailaddress">メールアドレス<input type="text" name="mailaddress" value="<?php echo set_value('mailaddress'); ?>" placeholder="メールアドレスを入力" maxlength="90" required></h3>
        <div class="error_mail"><?php echo form_error('mailaddress'); ?></div>
        <h3 class="regist_password">パスワード<input type="password" name="password" maxlength="12" placeholder="8文字以上12文字以内で入力" required></h3>
        <div class="error_pw"><?php echo form_error('password'); ?></div>
        <input class="regist_button" type="submit" name="submit" value="登録">
    </form>
    <p class="login_link">既にアカウントをお持ちの方は<a href="<?php echo site_url('login/login'); ?>">こちら</a></p>
</main>
