<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Registering a new administrator account') ?></h2>

<form action="<?php url_for('admin/addUser') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Add') ?>" /></td>
</tr>
</table>
</form>
