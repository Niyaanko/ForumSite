<main>
    <h2 class="catchcopy">イグナイトに登録して掲示板を利用しましょう！</h2>

    <?php echo validation_errors(); ?>

    <?php echo form_open('register/regist'); ?>
        <h2 class="form_title">アカウント登録</h2>
        <h3 class="regist_mailaddress">メールアドレス<input type="text" name='mailaddress' value='<?php echo set_value('mailaddress'); ?>'></h3>
        <h3 class="regist_password">パスワード<input type="password" name="password"></h3>
        <input class="regist_button" type="submit" name="submit" value="登録">
    </form>
    <p class="login_link">既にアカウントをお持ちの方は<a href="<?php echo site_url('login/login'); ?>">こちら</a></p>
</main>
