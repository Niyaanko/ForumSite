<main>
    <div class="top_space">
        <?php 
        // <form class="pulldown">生成
        $attribute_pulldown = array("class" => "pulldown");
        echo form_open("forum/index",$attribute_pulldown);
        // optionタグ設定
        $options = array(
            "threads.creation_datetime-DESC" => "スレッドが新しい順",
            "threads.creation_datetime-ASC" => "スレッドが古い順",
            "threads.title-DESC" => "かな順(降順)",
            "threads.title-ASC" => "かな順(昇順)",
            "comment_count-DESC" => "コメントが多い順",
            "comment_count-ASC" => "コメントが少ない順"
        );
        // selectedが存在しない場合"creation_datetime-DESC"を初期値に設定
        if(!isset($_SESSION['sort'])){
            $selected = "creation_datetime-DESC";
        }
        // 並び替えプルダウン生成 プルダウンの変更のみでsubmitするjs埋め込み
        echo form_dropdown("sorts", $options, $_SESSION['sort'],'onChange="this.form.submit()"');
        ?>
        </form>
        <a class="thread_create_link" href="<?php echo site_url('create/create')?>">スレッドを作成</a>
        <?php 
        // <form class="search" action="forum/index">生成
        $attribute_search = array("class" => "search");
        echo form_open("forum/index",$attribute_search);
        $val = "";
        if(isset($_SESSION['search'])){
            $val = $_SESSION['search'];
        }
        // <input type="text" value="$val" class="search" maxlength="20">
        $attributes_search = array(
            "name" => "search",
            "class" => "input_search",
            "value" => html_escape($val),
            "maxlength" => "20",
            "placeholder" => "スレッドを検索"
        );
        echo form_input($attributes_search);
        $attributes_submit = array(
            "name" => "submit",
            "value" => "検索",
            "class" => "search_btn"
        );
        echo form_submit($attributes_submit);
        ?>
        </form>
        <a class="clear_link" href="<?php echo site_url("forum/clear")?>">クリア</a>
    </div>
    <hr class="top_line">
    <?php 
    // msg(スレッドが存在しないメッセージ)がセットされている場合メッセージのみ表示
    if(isset($msg)){ ?>
        <h3 class="msg_none"><?php echo $msg; ?></h3>
    <?php 
    }else{
        // msg(スレッドが存在しないメッセージ)がセットされていない場合スレッドを10件ずつ表示
        // $threadsのクエリの数だけスレッド表示
        foreach($threads as $thread_item){
            $day = new DateTime($thread_item['creation_datetime']);
            $day = $day->format('Y年m月d日 H時i分');?>
    <div class="thread_link">
        <a href="<?php echo site_url("forum/view/".$thread_item['thread_id']); ?>">
            <span class="thread_title"><?php echo html_escape($thread_item['title']); ?></span>
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
    <div class="page_links"><?php if(isset($links)){echo $links; } ?></div>

</main>