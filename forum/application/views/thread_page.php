<main>
    <a class="top_link" href="<?= site_url("forum/index"); ?>">トップページへ戻る</a>
    <h2 class="thread_title" ><?= html_escape($thread['title']); ?></h2>
<?php 
$creator = $thread['creator'];
$creator_view_name = $creator['nickname'];
if($creator['permission'] === 'BANNED'):
    $creator_view_name = 'BANされたユーザー';
elseif($creator['permission'] === 'ADMIN'):
    $creator_view_name = '[管理者]'.$creator_view_name;
endif;
?>
    <h3 class="thread_creator" >スレッド作成者：<?= html_escape($creator_view_name);?></h3>
    <hr class="top_line">
    <div class="thread_content">
<?php 
if(empty($comments)): ?>
        <h3 class="comment_none">まだコメントが投稿されていません。</h3>
<?php 
else:
    // コメント最大100個表示
    $num = $page_data;
    foreach($comments as $comment_item): ?>
        <div class="comment_content">
<?php 
        $comment_day = new DateTime($comment_item['comment_datetime']);
        $comment_day = $comment_day->format('Y年m月d日 H時i分');
        // コメントの投稿者:に表示する名前を加工
        $commenter_view_name = $comment_item['nickname'];
        $commenter_name_class = 'commenter';
        if($comment_item['permission'] === 'BANNED'):
            $commenter_view_name = 'BANされたユーザー';
            $commenter_name_class = 'commenter_ban';
        elseif($comment_item['permission'] === 'ADMIN'):
            $commenter_view_name = '[管理者]'.$commenter_view_name;
            $commenter_name_class = 'commenter_admin';
        endif;
        // コメント投稿者が自分の場合、既に削除されている場合、投稿者がBANされている場合、
        // 投稿者が管理者の場合は通報リンクを表示しない
        // 既に自分が通報済みの場合は通報済みとのテキストのみ表示
        $user = $_SESSION['user'];
        $report_content = '<a href="'.site_url('forum/report/'.$comment_item["comment_id"]).'">コメントを通報</a>';
        if($comment_item['commenter_id'] === $user['user_id'] || $comment_item['status'] === 'DELETED' ||
            $comment_item['permission'] === 'BANNED' ||$comment_item['permission'] === 'ADMIN'):
            $report_content = '';
        elseif($comment_item['reported'] !== '0'):
            $report_content = '通報済み';
        endif;
        // 表示するコメントを加工
        $comment_text = $comment_item['text'];
        if($comment_item['status'] === 'DELETED'):
            $comment_text = '削除されたコメントです';
        elseif($comment_item['permission'] === 'BANNED'):
            $comment_text = '[BANされたユーザー]のコメントです';
        endif;      
?>
            <p class="top_row">
                <span class="comment_num"><?= ++$num; ?>.</span>
                投稿者:<span class="<?= $commenter_name_class; ?>"><?= html_escape($commenter_view_name); ?></span>
                <span class="creation_datetime">投稿日時:<?= $comment_day; ?></span>
                <span class="report_link"><?= $report_content; ?></span>
            </p>
            <hr class="comment_line">
            <pre class="comment_text"><?= html_escape($comment_text); ?></pre>
        </div>
<?php
    endforeach;
endif;
?>
    </div>

    <?= form_open('forum/view/'.$thread['thread_id']); ?>
        <hr class="bottom_line">
        <h3>コメントを投稿する</h3>
        <?php // コメント入力用テキストエリアの生成
        $data = array(
            'name' => 'comment',
            'value' => set_value('mailaddress'),
            'class' => 'comment_input',
            'maxlength' => '100',
            'placeholder' => 'コメントを書く'
        );?>
        <?= form_textarea($data); ?><br>
        <input class="send_button" type="submit" name="submit" value="投稿">
    </form>
    <a class="top_link_bottom" href="<?= site_url("forum/index"); ?>">トップページへ戻る</a>
    <div class="page_links"><?= $links; ?></div>
</main>
