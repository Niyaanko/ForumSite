<main>
    <a class="toplink_top" href="<?php echo site_url("admin/index");?>">管理者トップへ</a>
    <h2 class="main_title">ユーザー一覧</h2>
    <div class="users_body">
        <?php 
        if(isset($msg)){?>
        <h3><?php echo $msg; ?></h3>
        <?php
        }else{
            foreach($users as $user_item){?>
        <table class="user_content">
            <tr>
                <th>ユーザーID</th>
                <td><?php echo $user_item['user_id'];?></td>
                <th>ユーザー名</th>
                <td><?php echo $user_item['nickname'];?></td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td><?php echo $user_item['mailaddress'];?></td>
                <th>権限</th>
                <td><?php echo $user_item['permission'];?></td>
            </tr>
            <tr>
                <th>コメント数</th>
                <td><?php echo $user_item['comment_count'];?></td>
                <th>スレッド作成数</th>
                <td><?php echo $user_item['threads_create_count'];?></td>
            </tr>
            <tr>
                <th>通報されている数</th>
                <td><?php echo $user_item['report_count'];?></td>
                <th>削除されたコメント数</th>
                <td><?php echo $user_item['deleted_comment'];?></td>
            </tr>
            <tr class="row_link">
                <td colspan="4">
                    <?php 
                    if($user_item['permission'] === 'NORMAL'){ ?>
                    <a class="ban_link" href="<?php 
                        echo site_url("admin/ban/".$user_item['user_id']);
                        ?>">このユーザーをBAN</a>
                    <?php 
                    }elseif($user_item['permission'] === 'BANNED'){ ?>
                    <a class="recover_link" href="<?php 
                        echo site_url("admin/ban/".$user_item['user_id']);
                        ?>">BAN状態から回復</a>
                    <?php
                    }?>
                </td>
            </tr>
        </table>
        <?php
            }?>
        <div class="page_links"><?php echo $links; ?><div>
        <a  class="toplink_bottom" href="<?php echo site_url("admin/index");?>">管理者トップへ</a>
        <?php
        }?>
    </div>
</main>