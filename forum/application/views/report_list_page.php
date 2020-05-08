<main>
    <a class="toplink_top" href="<?php echo site_url("admin/index"); ?>">管理者トップへ</a>
    <h2 class="main_title">通報コメント一覧</h2>
    <div class="reports_body">
    <?php 
    if(isset($msg)){?>
    <h3><?php echo $msg; ?></h3>
    <?php 
    }else{ 
        foreach($report_comments as $report_item){
            $day = new DateTime($report_item['comment_datetime']);
            $day = $day->format('Y年m月d日 H時i分');?>
    <table class="report_content">
        <tr class="row_reportcount_thread">
            <th>通報数</th>
            <td class="report_count">
                <a href="<?php echo site_url("report/reports/".$report_item['commenter_id']); ?>"><?php echo $report_item['report_count']; ?></a>
            </td>
            <th>投稿スレッド</th>
            <td class="thread_title">
                <a href="<?php echo site_url("admin_threads/index/".$report_item['thread_id']); ?>"><?php echo html_escape($report_item['thread_title']); ?></a>
            </td>
        </tr>
        <tr class="row_commenter">
            <th>投稿者ID</th>
            <td>
                <a href="<?php echo site_url("report/users/".$report_item['thread_id']); ?>"><?php echo $report_item['commenter_id']; ?></a>
            </td>
            <th>投稿者名</th>
            <td>
                <a href="<?php echo site_url("report/users/".$report_item['thread_id']); ?>"><?php echo html_escape($report_item['commenter_name']); ?></a>                <?php 
            if($report_item['permission'] === 'BANNED'){ ?>
                <span>BAN済み</span>
            <?php } ?>
            </td>
        </tr>
        <tr class="row_comment_datetime">
            <th>投稿日時</th><td colspan="3"><?php echo $day; ?></td>
        </tr>
        <tr class="row_comment_text">
            <th>コメント</th>
            <td colspan="3">
                <pre><a href="<?php echo site_url("admin_threads/index/".$report_item['thread_id']."/".$report_item['comment_id']); ?>"><?php echo html_escape($report_item['comment_text']); ?></a></pre>
            </td>
        </tr>
        <tr class="row_links">
            <td colspan="4">
                <a class="delete_link" href="<?php echo site_url("report/delete/".$report_item['comment_id']); ?>">コメントを削除</a>
            <?php 
            if($report_item['permission'] !== 'BANNED'){ ?>
                <a class="ban_link" href="<?php echo site_url("report/ban/".$report_item['comment_id']); ?>">投稿者をBAN</a>
            <?php 
            }else{ ?>
                <p class="ban_done">投稿者をBAN</p>
            <?php 
            }?>
                <a class="ignore_link" href="<?php echo site_url("report/ignore/".$report_item['comment_id']); ?>">無視</a>
            </td>
        </tr>
    </table>
    <?php 
        }
    }?>
    </div>
    <a class="toplink_bottom" href="<?php echo site_url("admin/index"); ?>">管理者トップへ</a>
    <div class="page_links">
    <?php if(isset($links)){ echo $links; } ?>
    </div>
</main>