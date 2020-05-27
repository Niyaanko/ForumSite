<main>
    <h2 class="main_title">コメント通報</h2>
    <?php if(empty($msg)): ?>
    <table class="comment_info">
        <tr class="thread_title_line">
            <th>スレッド</th><td><?= html_escape($report_comment_thread['title']); ?></td>
        </tr>

        <tr class="commenter_line">
            <th>コメント投稿者</th><td><?= html_escape($report_comment_user['nickname']); ?></td>
        </tr>

        <tr class="comment_line">
            <th>コメント内容</th><td><pre><?= html_escape($report_comment['text']); ?></pre></td>
        </tr>
    </table>      
    <?= form_open("forum/report/".$report_comment['comment_id']);?>
        <input type="hidden" name="report_comment_id" value="<?= $report_comment['comment_id']; ?>">
        <input class ="report_btn" type="submit" name="submit" value="確定">
    </form>
    <a class="cancel_link" href="<?= site_url("forum/view/".$report_comment_thread['thread_id']); ?>">キャンセル</a>
    <?php else: ?>
    <h2 class="done_msg"><?= $msg; ?><h2>
    <a class="return_link" href="<?= site_url("forum/view/".$report_comment['thread_id']); ?>">スレッドへ戻る</a>
    <?php endif; ?>
</main>
