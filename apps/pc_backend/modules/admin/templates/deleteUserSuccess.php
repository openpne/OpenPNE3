<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('アカウント削除') ?></h2>

<p><?php echo __('このアカウントを本当に削除しますか？') ?></p>

<form action="<?php url_for('admin/deleteUser') ?>" method="post">
<table>
<tr>
<th><?php echo __('ID') ?></th><td><?php echo $user->getId() ?></td>
</tr>
<tr>
<th><?php echo __('ユーザ名') ?></th><td><?php echo $user->getUsername() ?></td>
</tr>
<tr>
<td colspan="2">
<?php echo $form ?>
<input type="submit" value="<?php echo __('削除') ?>" />
</td>
</tr>
</table>
</form>
