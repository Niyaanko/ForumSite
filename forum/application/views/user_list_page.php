<main>
    <a class="toplink_top" href="<?php echo site_url("admin/index");?>">管理者トップへ</a>
    <h2 class="main_title">ユーザー一覧</h2>
    <div class="users_body">
        <?php 
        if(isset($msg)){?>
        <h3><?php echo $msg; ?></h3>
        <?php
        }else{
            foreach($users as $user_item){
            echo form_open("admin/users");?>
            <table class="user_content">
                <tr>
                    <th>ユーザーID</th>
                    <td><?php echo $user_item['user_id'];?></td>
                    <th class="right_th">ユーザー名</th>
                    <td class="right_td"><?php echo $user_item['nickname'];?></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td><?php echo $user_item['mailaddress'];?></td>
                    <th class="right_th">権限</th>
                    <td class="right_td"><?php echo $user_item['permission'];?></td>
                </tr>
                <tr>
                    <th>コメント数</th>
                    <td><?php echo $user_item['comment_count'];?></td>
                    <th class="right_th">スレッド作成数</th>
                    <td class="right_td"><?php echo $user_item['threads_create_count'];?></td>
                </tr>
                <tr>
                    <th>通報されている数</th>
                    <td><?php echo $user_item['report_count'];?></td>
                    <th class="right_th">削除されたコメント数</th>
                    <td class="right_td"><?php echo $user_item['deleted_comment'];?></td>
                </tr>
                <tr class="row_link">
                    <td colspan="4">
                        <?php 
                        if($user_item['permission'] === 'NORMAL'){ ?>
                        <input type="hidden" name="ban_hdn" value="<?php echo $user_item['user_id']; ?>">
                        <input class="ban_btn" type="submit" name="submit" value="このユーザーをBAN">
                        <?php 
                        }elseif($user_item['permission'] === 'BANNED'){ ?>
                        <input type="hidden" name="recover_hdn" value="<?php echo $user_item['user_id']; ?>">
                        <input class="recover_btn" type="submit" name="submit" value="BAN状態から回復">
                        <?php
                        }?>
                    </td>
                </tr>
            </table>
            </form>
            <?php
                }?>
        <div class="page_links"><?php echo $links; ?></div>
        <?php
        }?>
    </div>
    <div class="bottom_link">
        <a class="toplink_bottom" href="<?php echo site_url("admin/index");?>">管理者トップへ</a>
    </div>
</main>