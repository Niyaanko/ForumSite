<main>
    <a class="top_link" href="<?php echo site_url("admin/threads"); ?>">スレッド一覧へ戻る</a>
    <h2 class="thread_title" ><?php echo html_escape($thread['title']); ?></h2>
    <h3 class="thread_creator" >スレッド作成者：<?php 
        $creator = $thread['creator'];
        if($creator['permission'] === 'NORMAL'){
            echo html_escape($creator['nickname']);
        }elseif($creator['permission'] === 'BANNED'){
            echo 'BANされたユーザー';
        }?></h3>
    <h3 class="thread_status">スレッドの状態:<?php echo $thread['status'] ;?></h3>
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
            echo form_open('admin/comments/'.$thread['thread_id']);
            $day = new DateTime($comment_item['comment_datetime']);
            $day = $day->format('Y年m月d日 H時i分');?>
                <p class="top_row">
                    <span class="comment_num"><?php echo ++$num; ?>.</span>
                    <span class="commenter_name">投稿者:<?php 
                    if($comment_item['permission'] === 'BANNED'){
                        echo '[BANされたユーザー]';
                    }elseif($comment_item['permission'] === 'ADMIN'){
                        echo '[管理者]';
                    }
                    echo html_escape($comment_item['nickname']);?></span>
                    <span>
                    <span class="reported_count">通報件数:<?php echo $comment_item['reported_count']; ?></span></p>
                </p>
                <p class="creation_datetime">投稿日時:<?php echo $day; ?></p>
                <p>コメントの状態:<?php 
                if($comment_item['status'] === 'NORMAL'){ ?>
                    <span class="comment_status">正常</span>
                    <?php if($comment_item['permission'] !== 'ADMIN'){?>
                    <input type="hidden" name="delete_hdn" value="<?php echo $comment_item['comment_id']; ?>">
                    <input class="delete_btn" type="submit" name="submit" value="コメントを削除">
                    <?php }?>
                <?php
                }elseif($comment_item['status'] === 'DELETED'){ ?>
                    <span class="comment_status">削除されたコメント</span>
                    <?php if($comment_item['permission'] !== 'ADMIN'){?>
                    <input type="hidden" name="recover_hdn" value="<?php echo $comment_item['comment_id']; ?>">
                    <input class="recover_btn" type="submit" name="submit" value="削除から回復">
                    <?php }?>
                <?php 
                } ?>
                
                <hr class="comment_line">
                <pre class="comment_text"><?php echo html_escape($comment_item['text']);?></pre>
            </form>
        </div>
        <?php
        }
    }?>
    </div>
    <?php 
    $data = array("class" => "add_comment");
    echo form_open('admin/comments/'.$thread['thread_id'],$data); ?>
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
    <a class="top_link_bottom" href="<?php echo site_url("admin/threads"); ?>">スレッド一覧へ戻る</a>
    <div class="page_links"><?php echo $links; ?></div>
</main>
