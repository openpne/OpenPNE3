<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Unsubscribe') ?></h2>

<p><?php echo __('Do you want to delete this member anyway?') ?></p>
<p><?php echo __('The data of this member will be lost forever.') ?></p>

<form action="<?php echo url_for('member/delete?id='.$member->getId()) ?>" method="post">
<table>
<tr>
<th><?php echo __('ID') ?></th><td><?php echo $member->getId() ?></td>
</tr>
<tr>
<th><?php echo __('Nickname') ?></th><td><?php echo $member->getName() ?></td>
</tr>
<?php foreach ($member->getProfiles() as $profile): ?>
<tr>
<th><?php echo $profile->getCaption() ?></th>
<td><?php echo $member->getProfile($profile->getName()) ?></td>
</tr>
<?php endforeach ?>
<tr>
<th><?php echo __('PC email') ?></th>
<td><?php echo $member->getConfig('pc_address') ?></td>
</tr>
<tr>
<th><?php echo __('Mobile email') ?></th>
<td><?php echo $member->getConfig('mobile_address') ?></td>
</tr>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Unsubscribe') ?>" /></td>
</tr>
</table>
</form>

<?php use_helper('Javascript') ?>
<?php echo link_to_function(__('Return to previous page'), 'history.back()') ?>
