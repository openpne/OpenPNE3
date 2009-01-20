<?php echo __('Setting mobile UID.'); echo __('Prease input your password, press "%1%" button.', array('%1%' => __('Save'))) ?>
<form action="<?php echo url_for('member/configUID') ?>" method="post">
<table>
<?php echo $passwordForm ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Save') ?>" /></td>
</tr>
</table>
</form>
