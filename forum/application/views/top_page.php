<main>

    <p><a href="<?php echo site_url('forum/create')?>">スレッドを作成</a></p>

    <hr>

    <?php 
    // msg(スレッドが存在しないメッセージ)がセットされている場合メッセージのみ表示
    if(isset($msg)){ 
        echo $msg; 
    // msg(スレッドが存在しないメッセージ)がセットされていない場合スレッドを10件ずつ表示
    }else{
        // $threadsのクエリの数だけスレッド表示
        foreach($threads as $thread_item){?>
    <p><a class="thread_link" href="<?php echo site_url("forum/view".$thread_item['thread_id']); ?>">
      <?php echo $thread_item['title'] ?></a><span class="coment_count">コメント数:<?php echo $thread_item['comment_count'] ?></span></p>
        <?php
        } ?>
    <?php 
    } ?>

    <?php echo $links ?>

</main>