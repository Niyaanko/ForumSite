<h2>イグナイトに登録して</h2>
<h2>掲示板を利用しましょう！</h2>

<?php echo validation_errors(); ?>

<?php echo form_open(''); ?>
    <h2>アカウント登録</h2>
    <h3>メールアドレス<input type="text" name="mailaddress"></h3>
    <h3>パスワード<input type="password" name="password"></h3>
    <input type="submit" name="submit" value="登録" />
</form>
<p>既にアカウントをお持ちの方は<a href="">こちら</a></p>