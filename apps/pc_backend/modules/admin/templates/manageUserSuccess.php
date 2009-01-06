<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('アカウント管理') ?></h2>

<p><?php echo __('管理用アカウントを設定します。') ?></p>
<p><?php echo link_to(__('アカウントを追加する'), 'admin/addUser') ?></p>

<table>
<tr>
<th><?php echo __('ID') ?></th>
<th><?php echo __('ユーザ名') ?></th>
<th><?php echo __('操作') ?></th>
</tr>
<?php foreach ($users as $user) : ?>
<tr>
<th><?php echo $user->getId() ?></th>
<td><?php echo $user->getUsername() ?></td>
<td><?php if ($user->getId() != 1 && $user->getId() != $sf_user->getId()) : ?>
<?php echo link_to(__('削除'), 'admin/deleteUser?id='.$user->getId()) ?>
<?php endif; ?></td>
</tr>
<?php endforeach; ?>
</table>
