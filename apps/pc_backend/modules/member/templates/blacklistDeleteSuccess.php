<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Delete Blacklist') ?></h2>

<?php echo __('Do you want to delete the following?') ?>

<form action="<?php echo url_for('member/blacklistDelete?id='.$blacklist->getId()) ?>" method="post">
<table>
<?php echo $form ?>
<tr><th><?php echo __('ID') ?></th><td><?php echo $blacklist->getId() ?></td></tr>
<tr><th><?php echo __('Mobile UID') ?></th><td><?php echo $blacklist->getUid() ?></td></tr>
<tr><th><?php echo __('Memo') ?></th><td><?php echo $blacklist->getMemo() ?></td></tr>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Delete') ?>" /></td>
</tr>
</table>
</form>
