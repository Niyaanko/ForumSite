<main>
    <h2 class="main_title">通報コメント投稿ユーザーBAN</h2>
<?php 
    $day = new DateTime($report_comment['comment_datetime']);
    $day = $day->format('Y年m月d日 H時i分');
?>
    <table class="report_content">
        <tr class="row_reportcount_thread">
            <th>通報数</th>
            <td class="report_count">
                <?= $report_comment['report_count']; ?>
            </td>
            <th>投稿スレッド</th>
            <td class="thread_title">
                <?= html_escape($report_comment['thread_title']); ?>
            </td>
        </tr>
        <tr class="row_commenter">
            <th>投稿者ID</th>
            <td>
                <?= $report_comment['commenter_id']; ?>
            </td>
            <th>投稿者名</th>
            <td>
                <?= html_escape($report_comment['commenter_name']); ?></a>
            </td>
        </tr>
        <tr class="row_comment_datetime">
            <th>投稿日時</th><td colspan="3"><?= $day; ?></td>
        </tr>
        <tr class="row_comment_text">
            <th>コメント</th>
            <td colspan="3">
                <pre><?= html_escape($report_comment['comment_text']); ?></pre>
            </td>
        </tr>
    </table>
    <h3 class="question_msg" >このコメントを投稿したユーザーをBANしますか？</h3>
    <?= form_open("report/ban/".$report_comment['comment_id']);?>
        <input type="hidden" name="ban_flg" value="TRUE">
        <input class="confirm_btn" type="submit" name="submit" value="確定">
    </form>
    <a class="cancel_link" href="<?= site_url("admin/reports"); ?>">キャンセル</a> 
</main>