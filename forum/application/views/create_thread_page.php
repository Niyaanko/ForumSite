<main>
    <h2 class="form_title" >スレッド作成</h2>
    <?php echo form_open('create/create_thread'); ?>
        <h3 class="input_label">スレッドのタイトル</h3>
        <input class="thread_title" type="text" name="title">
        <input class="create_button" type="submit" name="submit" value="作成">
    </form>
    <a class="cancel_link" href="<?php echo site_url("forum/index")?>">キャンセル</a>
</main>