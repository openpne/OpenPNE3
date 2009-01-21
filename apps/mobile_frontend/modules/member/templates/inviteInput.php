<?php op_mobile_page_title(__('Invite friends for %1%', array('%1%' => $op_config['sns_name']))) ?>

<form action="<?php echo url_for('member/invite') ?>" method="post">
<?php echo $form ?>
<br>
<input type="submit" value="<?php echo __('Send') ?>" />
</form>
