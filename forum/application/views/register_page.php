<main>
    <h2 class="catchcopy">イグナイトに登録して掲示板を利用しましょう！</h2>
<!-- アカウント登録フォーム -->
<?= form_open("register/regist"); ?>
        <table>
            <tr class="regist_title_row">
                <th></th>
                <th>アカウント登録</th>
            </tr>
            <tr class="regist_mailaddress_row">
                <th>メールアドレス</th>
                <td><input type="text" name="mailaddress" value="<?= html_escape(set_value('mailaddress')); ?>" placeholder="メールアドレスを入力" maxlength="90" required><td>
            </tr>
            <tr class="regist_mailaddress_error_row">
                <th></th>
                <td><?= form_error('mailaddress'); ?></td>
            </tr>
            <tr class="regist_password_row">
                <th>パスワード</th>
                <td>
                    <input class="js-password" type="password" name="password" maxlength="12" placeholder="8文字以上12文字以内で入力" required>
                    <div class="btn">
                        <input class="js-password-toggle" id="eye" type="checkbox">
                        <label class="js-password-label" for="eye"><i class="fas fa-eye"></i></label>
                    </div>
                </td>
            </tr>
            <tr class="regist_password_error_row">
                <th></th>
                <td><?= form_error('password'); ?></td>
            </tr>
            <tr class="regist_button_row">
                <th></th>
                <td><input type="submit" name="submit" value="登録"></td>
            </tr>
        </table>
    </form>
<!-- アカウント登録フォーム -->
<p class="login_link">既にアカウントをお持ちの方は<a href="<?= site_url('login/login'); ?>">こちら</a></p>
</main>
