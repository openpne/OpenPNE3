<?php op_mobile_page_title(__('Settings'), __('Setting easy login')) ?>
<?php if ($isSetMobileUid): ?>
<?php echo __('Mobile UID is already setting.') ?>
<hr color="<?php echo $op_color["core_color_11"] ?>">
<?php endif; ?>
<?php echo __('Setting mobile UID.'); echo __('Prease input your password, press "%1%" button.', array('%1%' => __('Save'))) ?>
<form action="<?php echo url_for('member/configUID').'?guid=on' ?>" method="post" utn>
<?php echo $passwordForm ?>
<center>
<input type="submit" value="<?php echo __('Save') ?>" name="update">
<?php if ($isDeletableUid): ?>
<input type="submit" value="<?php echo __('Delete') ?>" name="delete">
<?php endif ?>
</center>
</form>
