<main>

    <h2 class="main_title">マイページ</h2>
    <a class="logout_link" href="<?php echo site_url("mypage/logout"); ?>">ログアウト</a>
    <a class="top_link" href="<?php echo site_url("forum/index"); ?>">トップページへ戻る</a>
    <table>
            <?php 
            // セッションからuser情報を取得
            $user = $_SESSION['user']; ?>
            <tr>
                <th>ニックネーム</th>
                <td><?php echo $user['nickname']; ?></td>
                <td class="change_link_td">
                    <a class="change_link" href="<?php echo site_url("mypage/change/nickname"); ?>">変更</a>
                </td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td><?php echo $user['mailaddress']; ?></td>
                <td class="change_link_td">
                    <a class="change_link" href="<?php echo site_url("mypage/change/mailaddress"); ?>">変更</a>
                </td>
            </tr>
            <tr>
                <th>パスワード</th>
                <td>********<span class="security_comment">※セキュリティ保護のため表示していません</span></td>
                <td class="change_link_td">
                    <a class="change_link" href="<?php echo site_url("mypage/pw_change"); ?>">変更</a>
                </td>
            </tr>
            <tr>
                <th>スレッド作成数</th>
                <td><?php echo $threads_count; ?></td>
                <td class="change_link_td"></td>
            </tr>
            <tr>
                <th>コメント投稿数</th>
                <td><?php echo $comments_count; ?></td>
                <td class="change_link_td"></td>
            </tr>
    </table>
</main>