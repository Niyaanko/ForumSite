<main>
    <h2 class="main_title"><?php echo $change_label.'変更' ?></h2>
    <a class="my_link" href="<?php echo site_url("mypage/mypage"); ?>">マイページへ戻る</a>
    <?php echo form_open('mypage/change/'.$slug) ?>
        <table>
            <?php 
            // セッションからuser情報を取得
            $user = $_SESSION['user']; ?>
            <tr>
                <th>ニックネーム</th>
                <td>
                    <input type="text" name="nickname" value="<?php echo html_escape($user['nickname']); ?>"
                        <?php 
                        // $slugがnicknameじゃない場合はdisabled属性を付与
                        if($slug !== 'nickname'){?> disabled="disabled" <?php } ?>>
                    <div class="error_nickname"><?php echo form_error('nickname'); ?></div>
                </td>
                <td>
                    <?php 
                    // $slugがnicknameの場合のみ変更ボタンとキャンセルリンクを表示
                    if($slug === 'nickname'){?>
                    <input class="change_button" type="submit" name="submit" value="変更">
                    <a class="cancel_link" href="<?php echo site_url("mypage/mypage");?>">キャンセル</a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    <input type="text" name="mailaddress" value="<?php echo html_escape($user['mailaddress']); ?>"
                        <?php 
                        // $slugがmailaddressじゃない場合はdisabled属性を付与
                        if($slug !== 'mailaddress'){?> disabled="disabled" <?php } ?>>
                    <div class="error_mail"><?php echo form_error('mailaddress'); ?></div>
                </td>
                <td>
                    <?php 
                    // $slugがnicknameの場合のみ変更ボタンとキャンセルリンクを表示
                    if($slug === 'mailaddress'){?>
                    <input class="change_button" type="submit" name="submit" value="変更">
                    <a class="cancel_link" href="<?php echo site_url("mypage/mypage");?>">キャンセル</a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>パスワード</th>
                <td>********<br><span class="security_comment">※セキュリティ保護のため表示していません</span></td>
                <td></td>
            </tr>
            <tr>
                <th>スレッド作成数</th>
                <td><?php echo $threads_count; ?></td>
                <td></td>
            </tr>
            <tr>
                <th>コメント投稿数</th>
                <td><?php echo $comments_count; ?></td>
                <td></td>
            </tr>
        </table>
    </form>
</main>