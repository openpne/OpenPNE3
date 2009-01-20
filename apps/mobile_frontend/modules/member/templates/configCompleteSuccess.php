<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo __('Settings') ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>
<form action="<?php echo url_for(sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type'))) ?>" method="post">
<?php echo $form ?>
<br><br>
<center><input type="submit" value="<?php echo __('Send') ?>"></center>
</form>
