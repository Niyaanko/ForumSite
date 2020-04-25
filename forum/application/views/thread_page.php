<main>

    <p><a href="<?php echo site_url('forum/create')?>">スレッドを作成</a></p>

    <hr>

    <?php 
    // msg(スレッドが存在しないメッセージ)がセットされている場合メッセージ表示
    if(isset($msg)){ 
        echo $msg; 
    // msg(スレッドが存在しないメッセージ)がセットされていない場合スレッドを10件ずつ表示
    }else{?>
    
    

    <?php 
    } ?>
    
</main>