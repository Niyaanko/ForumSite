<main>
<h2 class="form_title">パスワード変更</h2>
    <?php 
        if(isset($success) && $success === TRUE)
        { 
        // パスワード変更に成功した場合 ?>
        <h3 class="success_msg">パスワードの変更が完了しました。</h3>
        <a class="maypage_link" href="<?php echo site_url("mypage/mypage")?>">マイページへ</a>
        <?php 
        }else{  
            if(isset($success) && $success === FALSE){ ?>
                <h3 class="failure_msg">パスワード変更に失敗しました</h3>
            <?php 
            }
        echo form_open('mypage/pw_change'); ?>
        <h3 class="password_conf">古いパスワード<input type="password" name="password_conf"></h3>
        <div class="error_conf"><?php echo form_error('password_conf'); ?></div>
        <h3 class="password_new">新しいパスワード<input type="password" name="password_new"></h3>
        <div class="error_new"><?php echo form_error('password_new'); ?></div>
        <input class="change_button" type="submit" name="submit" value="変更">
    </form>
    <a class="cancel_link" href="<?php echo site_url("mypage/mypage")?>">キャンセル</a>

        <?php 
        } ?>
</main>