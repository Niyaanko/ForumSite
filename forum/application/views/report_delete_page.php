<main>
    <h2 class="main_title">通報コメント削除</h2>
    <?php 
    $day = new DateTime($report_comment['comment_datetime']);
    $day = $day->format('Y年m月d日 H時i分');?>
    <table class="report_content">
        <tr class="row_reportcount_thread">
            <th>通報数</th>
            <td class="report_count">
                <?php echo $report_comment['report_count']; ?>
            </td>
            <th>投稿スレッド</th>
            <td class="thread_title">
                <?php echo html_escape($report_comment['thread_title']); ?>
            </td>
        </tr>
        <tr class="row_commenter">
            <th>投稿者ID</th>
            <td>
                <?php echo $report_comment['commenter_id']; ?>
            </td>
            <th>投稿者名</th>
            <td>
                <?php echo html_escape($report_comment['commenter_name']); ?></a>
            </td>
        </tr>
        <tr class="row_comment_datetime">
            <th>投稿日時</th><td colspan="3"><?php echo $day; ?></td>
        </tr>
        <tr class="row_comment_text">
            <th>コメント</th>
            <td colspan="3">
                <pre><?php echo html_escape($report_comment['comment_text']); ?></pre>
            </td>
        </tr>
    </table>
    <h3 class="question_msg" >このコメントを削除しますか？</h3>
    <?php 
    echo form_open("report/delete/".$report_comment['comment_id']);
    echo form_hidden('delete_flg', 'TRUE');
    $data = array(
        'name' => 'submit',
        'value' => '確定',
        'class' => 'confirm_btn'
    );
    echo form_submit($data); ?>
    <a class="cancel_link" href="<?php echo site_url("admin/reports"); ?>">キャンセル</a> 
</main>