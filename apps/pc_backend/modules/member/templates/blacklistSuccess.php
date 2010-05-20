<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Blacklist Management') ?></h2>

<h3><?php echo __('Add to blacklist') ?></h3>

<form action="<?php echo url_for('member/blacklist') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Add') ?>" /></td>
</tr>
</table>
</form>

<h3><?php echo __('Blacklist') ?></h3>

<?php if (!$pager->getNbResults()): ?>
<p><?php echo __('The blacklist is empty') ?></p>
<?php else: ?>
<table>

<tr>
<td colspan="4">
<?php op_include_pager_navigation($pager, 'member/list?page=%d', true, '?'.$sf_request->getCurrentQueryString()) ?>
</td>
</tr>

<tr>
<th><?php echo __('ID') ?></th>
<th><?php echo __('Mobile UID') ?></th>
<th><?php echo __('Memo') ?></th>
<th><?php echo __('Operation') ?></th>
</tr>

<?php foreach ($pager->getResults() as $blacklist): ?>
<tr style="background-color:<?php echo cycle_vars('member_list', '#fff, #eee') ?>;">
<td><?php echo $blacklist->getId() ?></td>
<td><?php echo $blacklist->getUid() ?></td>
<td><?php echo nl2br($blacklist->getMemo()) ?></td>
<td><?php echo link_to(__('Delete'), 'member/blacklistDelete?id='.$blacklist->getId()) ?></td>
</tr>
<?php endforeach; ?>

</table>
<?php endif; ?>
