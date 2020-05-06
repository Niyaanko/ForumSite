<main>
    <h2>通報コメント一覧</h2>
    <?php 
    if(isset($msg)){?>
    <h3><?php echo $msg; ?></h3>
    <?php 
    }else{ 
        foreach($report_comments as $report_item){?>
    <table>
        <tr>
            <th>通報数</th><td><a href=""><?php echo $report_item['report_count']; ?></a></td>
            <th>投稿スレッド</th><td><a href=""><?php echo $report_item['thread_title']; ?></a></td>
        </tr>
        <tr>
            <th>投稿者ID</th><td><a href=""><?php echo $report_item['commenter_id']; ?></a></td>
            <th>投稿者名</th><td><a href=""><?php echo $report_item['commenter_name']; ?></a></td>
        </tr>
        <tr>
            <th>投稿日時</th><td><a href=""><?php echo $report_item['comment_datetime']; ?></a></td>
        </tr>
        <tr>
            <th>コメント</th><td><pre><a href=""><?php echo $report_item['comment_text']; ?></a></pre><td>
        </tr>
    </table>
    <?php 
        }
    }?>
</main>