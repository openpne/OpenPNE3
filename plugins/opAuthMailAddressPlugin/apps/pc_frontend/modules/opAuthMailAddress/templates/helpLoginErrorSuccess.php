<?php slot('_nav_to_password_recovery'); ?>
<p><?php echo __('Please click the following button to start the password recovery process.'); ?></p>
<form action="<?php echo url_for('opAuthMailAddress/passwordRecovery') ?>">
<input type="submit" class="input_submit" value="<?php echo __('Go to Password Recovery Page') ?>" />
</form>
<?php end_slot(); ?>

<?php op_include_box('NavToPasswordRecoveryBox', get_slot('_nav_to_password_recovery'), array('title' => __('Do you forget your password?'))); ?>

