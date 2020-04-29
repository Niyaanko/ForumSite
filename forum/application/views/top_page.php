<main>

    <p><a class="thread_create_link" href="<?php echo site_url('forum/create')?>">スレッドを作成</a></p>

    <hr class="top_line">

    <?php 
    // msg(スレッドが存在しないメッセージ)がセットされている場合メッセージのみ表示
    if(isset($msg)){ 
        echo $msg; 
    // msg(スレッドが存在しないメッセージ)がセットされていない場合スレッドを10件ずつ表示
    }else{
        // $threadsのクエリの数だけスレッド表示
        foreach($threads as $thread_item){
            $day = new DateTime($thread_item['creation_datetime']);
            $day = $day->format('Y年m月d日 H時i分');?>
    <div class="thread_link">
        <a href="<?php echo site_url("forum/view".$thread_item['thread_id']); ?>">
            <span class="thread_title"><?php echo $thread_item['title'] ?></span>
            <p class="bottom_row">
                <span class="coment_count">コメント数:<?php echo $thread_item['comment_count']; ?></span>
                <span class="creation_datetime">作成日時:<?php echo $day; ?></span>
            </p>
        </a>
    </div>
        <?php
        } ?>
    <?php 
    } ?>

    <hr class="bottom_line">

    <div class="page_links"><?php echo $links ?></div>

</main>