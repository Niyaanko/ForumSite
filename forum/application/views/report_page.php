<main>

    <h2 class="main_title">コメント通報</h2>
    <?php if(empty($msg)){ ?>
    <table class="comment_info">
        <tr class="thread_title_line">
            <th>スレッド</th><td><?php echo html_escape($report_comment_thread['title']); ?></td>
        </tr>

        <tr class="commenter_line">
            <th>コメント投稿者</th><td><?php echo html_escape($report_comment_user['nickname']); ?></td>
        </tr>

        <tr class="comment_line">
            <th>コメント内容</th><td><pre><?php echo html_escape($report_comment['text']); ?></pre></td>
        </tr>
    </table>      
    <?php echo form_open("forum/report/".$report_comment['comment_id']);?>
        <input type="hidden" name="report_comment_id" value="<?php echo $report_comment['comment_id']; ?>">
        <?php
        $attributes_submit = array(
        "name" => "submit",
        "value" => "確定",
        "class" => "report_btn"
        );
        echo form_submit($attributes_submit);
        ?>
    </form>
    <a class="cancel_link" href="<?php echo site_url("forum/view/".$report_comment_thread['thread_id']); ?>">キャンセル</a>
    <?php }else{ ?>
    <h2 class="done_msg"><?php echo $msg; ?><h2>
    <a class="return_link" href="<?php echo site_url("forum/view/".$report_comment['thread_id']); ?>">スレッドへ戻る</a>
    <?php } ?>
</main>
