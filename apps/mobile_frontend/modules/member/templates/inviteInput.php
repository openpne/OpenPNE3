<?php include_page_title('友人を'.opConfig::get('sns_name').'に招待する') ?>

<form action="<?php echo url_for('member/invite') ?>" method="post">
<?php echo $form ?>
<br>
<input type="submit" value="<?php echo __('送信') ?>" />
</form>
