<div class="dparts form"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Member Registration') ?></h3></div>
<form action="<?php echo url_for('member/registerInput') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" class="input_submit" value="<?php echo __('Register') ?>" /></td>
</tr>
</table>
</form>
</div></div>
