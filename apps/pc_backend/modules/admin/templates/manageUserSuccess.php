<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Account management') ?></h2>

<p><?php echo __('Set administrator account.') ?></p>
<p><?php echo link_to(__('Registering a new administrator account'), 'admin/addUser') ?></p>

<table>
<tr>
<th><?php echo __('ID') ?></th>
<th><?php echo __('User name') ?></th>
<th><?php echo __('Operation') ?></th>
</tr>
<?php foreach ($users as $user) : ?>
<tr>
<th><?php echo $user->getId() ?></th>
<td><?php echo $user->getUsername() ?></td>
<td><?php if ($user->getId() != 1 && $user->getId() != $sf_user->getId()) : ?>
<?php echo link_to(__('Delete'), 'admin/deleteUser?id='.$user->getId()) ?>
<?php endif; ?></td>
</tr>
<?php endforeach; ?>
</table>
