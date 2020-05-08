<main>
    <a class="top_link" href="<?php echo site_url("forum/index"); ?>">トップページへ戻る</a>
    <h2 class="thread_title" ><?php echo html_escape($thread['title']); ?></h2>
    <h3 class="thread_creator" >スレッド作成者：<?php 
        $creator = $thread['creator'];
        if($creator['permission'] === 'NORMAL'){
            echo html_escape($creator['nickname']);
        }elseif($creator['permission'] === 'BANNED'){
            echo 'BANされたユーザー';
        }?></h3>
    <hr class="top_line">
    <div class="thread_content">
    <?php 
    if(empty($comments)){ ?>
        <h3 class="comment_none">まだコメントが投稿されていません。</h3>
    <?php 
    }else{
    // コメント最大100個表示
        $num = $page_data;
        foreach($comments as $comment_item){ ?>
        <div class="comment_content">
    <?php 
            $day = new DateTime($comment_item['comment_datetime']);
            $day = $day->format('Y年m月d日 H時i分');?>
            <p class="top_row">
                <span class="comment_num"><?php echo ++$num; ?>.</span>
                <?php 
                // permissionが1の場合は投稿者をそのまま表示
                if($comment_item['permission'] === 'NORMAL'){ ?>
                <span class="commenter">投稿者:<?php echo html_escape($comment_item['nickname']); ?></span>
                <?php 
                // permissionが-1の場合は「BANされたユーザー」を表示
                }elseif($comment_item['permission'] === 'BANNED'){ ?>
                投稿者:<span class="commenter_ban">BANされたユーザー</span>
                <?php 
                }elseif($comment_item['permission'] === 'ADMIN'){ ?>
                投稿者:<span class="commenter_admin">[管理者]<?php echo html_escape($comment_item['nickname']); ?></span>
                <?php 
                }?>
                <span class="creation_datetime">投稿日時:<?php echo $day; ?>
                <?php 
                // 投稿者が自分の場合、BAN済みの場合、管理者の場合通報リンクを非表示
                $user = $_SESSION['user'];
                if($comment_item['commenter_id'] === $user['user_id'] || $comment_item['status'] === 'DELETED' ||
                    $comment_item['permission'] === 'BANNED' ||$comment_item['permission'] === 'ADMIN'){
                    echo '';
                }elseif($comment_item['reported'] !== '0'){?>
                <span class="report_link">通報済み</span>
                <?php 
                }elseif($comment_item['reported'] === '0'){?>
                <span class="report_link"><a href="<?php echo site_url("forum/report/".$comment_item['comment_id']);?>">コメントを通報</a></span>
                <?php  
                }?>
            </p>

            <hr class="comment_line">
            <pre class="comment_text"><?php 
                if($comment_item['status'] === 'DELETED'){
                    echo '[削除されたコメント]です';
                }elseif($comment_item['permission'] === 'NORMAL' || $comment_item['permission'] === 'ADMIN'){
                    echo html_escape($comment_item['text']);
                }elseif($comment_item['permission'] === 'BANNED'){
                    echo '[BANされたユーザー]のコメントです';
                }?></pre>
        </div>
        <?php
        }
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
    <a class="top_link_bottom" href="<?php echo site_url("forum/index"); ?>">トップページへ戻る</a>
    <div class="page_links"><?php echo $links; ?></div>
</main>
