<main>
<!-- ログインフォーム -->
<?= form_open("login/login"); ?>
        <table>
            <tr class="login_title_row">
                <th></th>
                <th>ログイン</th>
            </tr>
            <tr class="login_mailaddress_row">
                <th>メールアドレス</th>
                <td><input type="text" name="mailaddress" value="<?= html_escape(set_value('mailaddress')); ?>"placeholder="メールアドレスを入力" required><td>
            </tr>
            <tr class="login_mailaddress_error_row">
                <th></th>
                <td><?= form_error('mailaddress'); ?></td>
            </tr>
            <tr class="login_password_row">
                <th>パスワード</th>
                <td>
                    <input class="js-password" type="password" name="password" placeholder="パスワードを入力" maxlength="12" autocomplete="off" required>
                    <div class="btn">
                        <input class="js-password-toggle" id="eye" type="checkbox">
                        <label class="js-password-label" for="eye"><i class="fas fa-eye"></i></label>
                    </div>
                </td>
            </tr>
            <tr class="login_password_error_row">
                <th></th>
                <td><?php if(isset($error)):?><?= $error ?><?php endif; ?></td>
            </tr>
            <tr class="login_button_row">
                <th></th>
                <td><input type="submit" name="submit" value="ログイン" ></td>
            </tr>
        </table>
    </form>
<!-- ログインフォーム -->
    <p class="regist_link">アカウントをお持ちでない方は<a href=<?= site_url("register/regist"); ?>>こちら</a></p>
</main>
