<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo $community->getName() ?></a></font><br>
</td></tr>
<tr><td align="center" bgcolor="#DDDDDD">
<font color="#000000"><a name="top"><?php echo __('Edit community') ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>

<?php if ($form->isNew()) : ?>
<form action="<?php echo url_for('community/edit') ?>" method="post">
<?php else : ?>
<form action="<?php echo url_for('community/edit?id=' . $community->getId()) ?>" method="post">
<?php endif; ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Save') ?>" /></td>
</tr>
</table>
</form>
