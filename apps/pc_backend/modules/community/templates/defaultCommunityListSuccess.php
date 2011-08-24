<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('Default %community% Configuration')); ?>

<p><?php echo __('You can set new members to join the following %community% automatically.') ?></p>
<p><?php echo __('Input the %community% ID you want new members to join and click "Add" button.') ?></p>

<form action="<?php url_for('community/defaultCommunityList') ?>" method="post">
<table>
<?php $form->getWidgetSchema()->setLabel("id", __('ID')); ?>
<?php echo $form ?>
<tr><td colspan="2"><input type="submit" value=<?php echo __('Add') ?>></td></tr>
</table>
</form>

<?php if ($communities): ?>
<table>
<tr>
<th>ID</th>
<th><?php echo __('%Community% Name') ?></th>
<th><?php echo __('Administrator') ?></th>
<th><?php echo __('Operation') ?></th>
</tr>
<?php foreach ($communities as $community): ?>
<tr>
<td><?php echo $community->getId() ?></td>
<td><?php echo $community->getName() ?></td>
<td><?php echo $community->getAdminMember()->getName() ?></td>
<td><?php echo link_to(__('Delete'), 'community/removeDefaultCommunity?id='.$community->getId()) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
