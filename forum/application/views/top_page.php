<main>
    <div class="top_space">
<?php 
// <form class="pulldown">生成
$attribute_pulldown = array("class" => "pulldown");
?>
<?= form_open("forum/index",$attribute_pulldown);?>
<?php
// optionタグ設定
$options = array(
    "threads.creation_datetime-DESC" => "スレッドが新しい順",
    "threads.creation_datetime-ASC" => "スレッドが古い順",
    "threads.title-DESC" => "かな順(降順)",
    "threads.title-ASC" => "かな順(昇順)",
    "comment_count-DESC" => "コメントが多い順",
    "comment_count-ASC" => "コメントが少ない順"
);
?>
<?=
// 並び替えプルダウン生成 プルダウンの変更のみでsubmitするjs埋め込み
form_dropdown("sorts", $options, $_SESSION['sort'],'onChange="this.form.submit()"');?>
        </form>
        <a class="thread_create_link" href="<?= site_url('create/create')?>">スレッドを作成</a>
<?php 
// <form class="search" action="forum/index">生成
$attribute_search = array("class" => "search");?>
<?= form_open("forum/index",$attribute_search);?>
<?php
$val = "";
if(isset($_SESSION['search'])):
    $val = $_SESSION['search'];
endif;
?>
            <input class="input_search" type="text" name="search" value="<?= html_escape($val); ?>" maxlength="20" placeholder="スレッドを検索">
            <input class="search_btn" type="submit" name="submit" value="検索">
        </form>
        <a class="clear_link" href="<?= site_url("forum/clear")?>">クリア</a>
    </div>
    <hr class="top_line">
<?php 
    // msg(スレッドが存在しないメッセージ)がセットされている場合メッセージのみ表示
    if(isset($msg)): ?>
        <h3 class="msg_none"><?php echo $msg; ?></h3>
<?php 
    else:
        // msg(スレッドが存在しないメッセージ)がセットされていない場合スレッドを10件ずつ表示
        // $threadsのクエリの数だけスレッド表示
        foreach($threads as $thread_item):
            $day = new DateTime($thread_item['creation_datetime']);
            $day = $day->format('Y年m月d日 H時i分');
?>
    <div class="thread_link">
        <a href="<?= site_url("forum/view/".$thread_item['thread_id']); ?>">
            <span class="thread_title"><?= html_escape($thread_item['title']); ?></span>
            <p class="bottom_row">
                <span class="coment_count">コメント数:<?= $thread_item['comment_count']; ?></span>
                <span class="creation_datetime">作成日時:<?= $day; ?></span>
            </p>
        </a>
    </div>
<?php
        endforeach; ?>
<?php 
    endif; ?>
    <hr class="bottom_line">
    <div class="page_links"><?= $links;?></div>
</main>