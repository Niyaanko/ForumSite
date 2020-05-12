<main>
    <!-- 管理者スレッドページトップ部 -->
    <div class="top_space">
        <a class="toplink_top" href="<?php echo site_url("admin/index"); ?>">トップページへ戻る</a>
        <?php 
        // <form class="pulldown">生成
        $attribute_pulldown = array("class" => "pulldown");
        echo form_open("admin/threads",$attribute_pulldown);
        $options = array(
            "threads.creation_datetime-DESC" => "スレッドが新しい順",
            "threads.creation_datetime-ASC" => "スレッドが古い順",
            "threads.title-DESC" => "かな順(降順)",
            "threads.title-ASC" => "かな順(昇順)",
            "comment_count-DESC" => "コメントが多い順",
            "comment_count-ASC" => "コメントが少ない順"
        );
        $selected = "creation_datetime-DESC";
        if(isset($_SESSION['sort'])){
            $selected = $_SESSION['sort'];
        }
        echo form_dropdown("sorts", $options, $selected,'onChange="this.form.submit()"');?>
        </form>
        <?php 
        // <form class="search" action="forum/index">生成
        $attribute_search = array("class" => "search");
        echo form_open("admin/threads",$attribute_search);
        $val = "";
        if(isset($_SESSION['search'])){
            $val = $_SESSION['search'];
        }
        ?>
        <input class="input_search" type="text" name="search" value="<?php echo html_escape($val);?>" maxlength="20" placeholder="スレッドを検索">
        <input class="search_btn" type="submit" name="submit" value="検索">
        </form>
        <a class="clear_link" href="<?php echo site_url("admin/clear")?>">クリア</a>
    </div>
    <hr class="top_line">
    <!-- 管理者スレッドページトップ -->
    <!-- スレッド一覧 -->
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
        <a href="<?php echo site_url("admin/comments/".$thread_item['thread_id']); ?>">
            <span class="thread_title">スレッドタイトル:<?php echo html_escape($thread_item['title']); ?></span>
            <p class="status_row">状態:<?php echo html_escape($thread_item['thread_status']); ?></p>
            <p class="info_row">
                <span class="coment_count">コメント数:<?php echo $thread_item['comment_count']; ?></span>
                <span class="creation_datetime">作成日時:<?php echo $day; ?></span>
            </p>
            <div class="bottom_row" >
                <?php echo form_open("admin/threads");
                if($thread_item['thread_status'] === 'BANNED'){?>
                    <input  type="hidden" name="recover_hdn" value="<?php echo $thread_item['thread_id'];?>">
                    <input class="recover_btn" type="submit" name="submit" value="BAN状態から回復">
                <?php 
                }elseif($thread_item['thread_status'] === 'NORMAL'){?>
                    <input type="hidden" name="ban_hdn" value="<?php echo $thread_item['thread_id'];?>">
                    <input class="ban_btn" type="submit" name="submit" value="このスレッドをBAN">
                <?php
                }?>
                </form>
            </div>
        </a>
    </div>
        <?php
        } ?>
    <!-- スレッド一覧 -->
    <?php 
    } ?>
    <hr class="bottom_line">
    <div class="page_links"><?php if(isset($links)){echo $links; } ?></div>
    <div class="toplink_area">
        <a class="toplink_bottom" href="<?php echo site_url("admin/index"); ?>">トップページへ戻る</a>
    </div>
</main>