<h2>ログイン</h2>

<?php echo validation_errors(); ?>
<?php if(isset($error)){ echo $error; }?>

<?php echo form_open('login/login'); ?>
    <h3>メールアドレス<input type="text" name="mailaddress" value="<?php echo set_value('mailaddress'); ?>"></h3>
    <h3>パスワード<input type="password" name="password"></h3>
    <input type="submit" name="submit" value="ログイン" />
</form>
<p>アカウントをお持ちでない方は<a href=<?php echo site_url("register/regist"); ?>>こちら</a></p>