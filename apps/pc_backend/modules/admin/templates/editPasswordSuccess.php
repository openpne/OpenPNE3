<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Change Password') ?></h2>

<form action="<?php url_for('admin/editPassword') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Setting') ?>" /></td>
</tr>
</table>
</form>
