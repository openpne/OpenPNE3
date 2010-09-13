<?php op_mobile_page_title(__('Register mobile')) ?>

<?php echo __('You register mobile UID.') ?><br>
<?php echo __('Prease input your password, press "%1%" button.', array('%1%' => __('Register'))) ?><br>

<form action="<?php echo url_for('member/registerMobileToRegisterEnd?token='.$sf_params->get('token').'&id='.$sf_params->get('id')).'?guid=on' ?>" method="post" utn>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Register') ?>">
</form>
