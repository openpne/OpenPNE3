<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Delete account') ?></h2>

<p><?php echo __('Are you sure you want to delete this account?') ?></p>

<form action="<?php url_for('admin/deleteUser') ?>" method="post">
<table>
<tr>
<th><?php echo __('ID') ?></th><td><?php echo $user->getId() ?></td>
</tr>
<tr>
<th><?php echo __('User name') ?></th><td><?php echo $user->getUsername() ?></td>
</tr>
<tr>
<td colspan="2">
<?php echo $form ?>
<input type="submit" value="<?php echo __('Delete') ?>" />
</td>
</tr>
</table>
</form>
