<?php op_mobile_page_title(__('Settings')) ?>

<form action="<?php echo url_for(sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type'))) ?>" method="post">
<?php echo $form ?>
<br><br>
<center><input type="submit" value="<?php echo __('Send') ?>"></center>
</form>
