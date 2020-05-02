<main>

    <h2 class="thread_title" ><?php echo $thread['title']; ?></h2>
    <hr class="top_line">
    <div class="thread_content">
    <?php 
    if(empty($comments)){ ?>
        <h3 class="comment_none">まだコメントが投稿されていません。</h3>
    <?php 
    }else{
    // コメント最大100個表示
        foreach($comments as $comment_item){ ?>
        <div class="comment_content">
    <?php 
            $day = new DateTime($comment_item['comment_datetime']);
            $day = $day->format('Y年m月d日 H時i分');?>
            <p class="top_row">
                <span class="comment_num"><?php echo $comment_item['comment_id']; ?>.</span>
                <span class="coment_count">投稿者:<?php echo $comment_item['nickname']; ?></span>
                <span class="creation_datetime">投稿日時:<?php echo $day; ?></span>
            </p>
            <pre class="comment_text"><?php echo $comment_item['text']; ?></pre>
        </div>
    <?php
    } ?> 
    
    <?php 
    }?>
    </div>

    <?php echo form_open('forum/view/'.$thread['thread_id']); ?>
        <hr class="bottom_line">
        <h3>コメントを投稿する</h3>
        <?php // コメント入力用テキストエリアの生成
        $data = array(
            'name' => 'comment',
            'value' => set_value('mailaddress'),
            'class' => 'comment_input',
            'maxlength' => '100',
            'placeholder' => 'コメントを書く'
        );
        echo form_textarea($data); ?><br>
        <input class="send_button" type="submit" name="submit" value="投稿">
    </form>
</main>