<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo __('Register mobile') ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>

<?php echo __('You register mobile UID.') ?><br>
<?php echo __('Prease input your password, press "%1%" button.', array('%1%' => __('Register'))) ?><br>

<form action="<?php echo url_for('member/registerMobileToRegisterEnd?token='.$sf_params->get('token').'&id='.$sf_params->get('id')) ?>" method="post" utn>
<?php echo $form ?>
<input type="submit" value="<?php echo __('Register') ?>">
</form>
