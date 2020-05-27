<main>
    <h2 class="main_title"><?= $change_label.'変更' ?></h2>
    <a class="my_link" href="<?= site_url("mypage/mypage"); ?>">マイページへ戻る</a>
<?= form_open('mypage/change/'.$slug) ?>
        <table>
<?php 
// セッションからuser情報を取得
$user = $_SESSION['user'];
// 変更されない方にdisabled属性を追加
$nickname_disabled = '';
$mailaddress_disabled = '';
if($slug === 'nickname'):
    $mailaddress_disabled = 'disabled="disabled"';
elseif($slug === 'mailaddress'):
    $nickname_disabled = 'disabled="disabled"';
endif;
?>
            <tr>
                <th>ニックネーム</th>
                <td>
                    <input type="text" name="nickname" value="<?= html_escape($user['nickname']);?>" <?= $nickname_disabled;?>>
                    <div class="error_nickname"><?= form_error('nickname'); ?></div>
                </td>
                <td>
                    <?php 
                    // $slugがnicknameの場合のみ変更ボタンとキャンセルリンクを表示
                    if($slug === 'nickname'){?>
                    <input class="change_button" type="submit" name="submit" value="変更">
                    <a class="cancel_link" href="<?= site_url("mypage/mypage");?>">キャンセル</a>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td>
                    <input type="text" name="mailaddress" value="<?= html_escape($user['mailaddress']); ?>" <?= $mailaddress_disabled;?>>
                    <div class="error_mail"><?= form_error('mailaddress'); ?></div>
                </td>
                <td>
                    <?php 
                    // $slugがnicknameの場合のみ変更ボタンとキャンセルリンクを表示
                    if($slug === 'mailaddress'){?>
                    <input class="change_button" type="submit" name="submit" value="変更">
                    <a class="cancel_link" href="<?= site_url("mypage/mypage");?>">キャンセル</a>
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
                <td><?= $threads_count; ?></td>
                <td></td>
            </tr>
            <tr>
                <th>コメント投稿数</th>
                <td><?= $comments_count; ?></td>
                <td></td>
            </tr>
        </table>
    </form>
</main>