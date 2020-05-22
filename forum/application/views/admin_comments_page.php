<main>
    <a class="top_link" href="<?= site_url("admin/threads"); ?>">スレッド一覧へ戻る</a>
    <h2 class="thread_title" ><?= html_escape($thread['title']); ?></h2>
<?php 
$creator = $thread['creator'];
$creator_supplement = '';
if($creator['permission'] === 'BANNED'):
    $creator_supplement = '[BANされたユーザー]';
elseif($creator['permission'] === 'ADMIN'):
    $creator_supplement = '[管理者]';
endif;
?>
    <h3 class="thread_creator" >スレッド作成者：<?= html_escape($creator_supplement.$creator['nickname']);?></h3>
    <h3 class="thread_status">スレッドの状態:<?php echo $thread['status'] ;?></h3>
    <hr class="top_line">
    <div class="thread_content">
<?php 
if(empty($comments)):
?>
        <h3 class="comment_none">まだコメントが投稿されていません。</h3>
<?php 
else:
    // コメント最大100個表示
    $num = $page_data;
    foreach($comments as $comment_item):?>
        <div class="comment_content">           
<?= form_open('admin/comments/'.$thread['thread_id']);?>
<?php 
        $day = new DateTime($comment_item['comment_datetime']);
        $day = $day->format('Y年m月d日 H時i分');
        $commenter_supplement = '';
        if($comment_item['permission'] === 'BANNED'):
            $commenter_supplement = '[BANされたユーザー]';
        elseif($comment_item['permission'] === 'ADMIN'):
            $commenter_supplement = '[管理者]';
        endif;      
?>
                <p class="top_row">
                    <span class="comment_num"><?= ++$num;?>.</span>
                    <span class="commenter_name">投稿者:<?= html_escape($commenter_supplement.$comment_item['nickname']);?></span>
                    <span class="reported_count">通報件数:<?= $comment_item['reported_count']; ?></span>
                </p>
                <p class="creation_datetime">投稿日時:<?= $day; ?></p>
<?php 
        $comment_status = '正常';
        if($comment_item['status'] === 'DELETED'):
            $comment_status = '削除';
        endif;
?>
                <p>コメントの状態:<span class="comment_status"><?= $comment_status;?></span>
<?php 
        if($comment_item['status'] === 'NORMAL' && $comment_item['permission'] !== 'ADMIN'):?>
                <input type="hidden" name="delete_hdn" value="<?= $comment_item['comment_id']; ?>">
                <input class="delete_btn" type="submit" name="submit" value="コメントを削除">
<?php
        elseif($comment_item['status'] === 'DELETED' && $comment_item['permission'] !== 'ADMIN'):?>
                <input type="hidden" name="recover_hdn" value="<?php echo $comment_item['comment_id']; ?>">
                <input class="recover_btn" type="submit" name="submit" value="削除から回復"> 
<?php 
        endif;?>
                </p>
                <hr class="comment_line">
                <pre class="comment_text"><?= html_escape($comment_item['text']);?></pre>
            </form>
        </div>
<?php
    endforeach;
endif;
?>
    </div>
<?php 
    $data = array("class" => "add_comment");?>
<?= form_open('admin/comments/'.$thread['thread_id'],$data); ?>
        <hr class="bottom_line">
        <h3>コメントを投稿する</h3>
<?php // コメント入力用テキストエリアの生成
    $data = array(
        'name' => 'comment',
        'value' => html_escape(set_value('comment')),
        'class' => 'comment_input',
        'maxlength' => '100',
        'placeholder' => 'コメントを書く'
    );
?>
<?= form_textarea($data); ?><br>
        <input class="send_button" type="submit" name="submit" value="投稿">
    </form>
    <a class="top_link_bottom" href="<?= site_url("admin/threads"); ?>">スレッド一覧へ戻る</a>
    <div class="page_links"><?= $links; ?></div>
</main>
