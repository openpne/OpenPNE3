<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('Members')); ?>

<form action="<?php echo url_for('member/list') ?>" method="get">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value=<?php echo __('Search') ?> /></td>
</tr>
</table>
</form>

<?php if (!$pager->getNbResults()): ?>
<p><?php echo __('No members matching') ?></p>
<?php else: ?>

<p>
<?php echo image_tag('backend/icn_delete_account.gif', array('alt' => __('Unsubscribe'))) ?>: <?php echo __('Unsubscribe') ?>
&nbsp;
<?php echo image_tag('backend/icn_rejected.gif', array('alt' => __('Ban'))) ?>: <?php echo __('Ban') ?>
&nbsp;
<?php echo image_tag('backend/icn_permit.gif', array('alt' => __('Unban'))) ?>: <?php echo __('Unban') ?>
&nbsp;
<?php echo image_tag('backend/icn_passwd.gif', array('alt' => __('Reissue password'))) ?>: <?php echo __('Reissue password') ?>
&nbsp;
<?php echo image_tag('backend/icn_blacklist.gif', array('alt' => __('Add mobile UID to blacklist'))) ?>: <?php echo __('Add mobile UID to blacklist') ?>
</p>

<table>

<tr>
<td colspan="<?php echo 7 + count($profiles) + 4 ?>">
<?php op_include_pager_navigation($pager, 'member/list?page=%d', array('use_current_query_string' => true)) ?>
</td>
</tr>

<tr>
<th colspan="4"><?php echo __('Operation') ?></th>
<th><?php echo __('ID') ?></th>
<th><?php echo __('Nickname') ?></th>
<th><?php echo __('Invited by') ?></th>
<th><?php echo __('Last login') ?></th>
<?php foreach ($profiles as $profile) : ?>
<?php if ($profile->isPreset()): ?>
<?php $config = $profile->getPresetConfig(); ?>
<th><?php echo __($config['Caption']) ?></th>
<?php else: ?>
<th><?php echo $profile->getCaption() ?></th>
<?php endif; ?>
<?php endforeach; ?>
<th><?php echo __('PC email') ?></th>
<th><?php echo __('Mobile email') ?></th>
<th><?php echo __('Mobile UID') ?></th>
</tr>

<?php foreach ($pager->getResults() as $i => $member): ?>
<tr style="background-color:<?php echo cycle_vars('member_list', '#fff, #eee') ?>;">
<td>
<?php if ($member->getId() != 1) : ?>
<?php echo link_to(image_tag('backend/icn_delete_account.gif', array('alt' => __('Unsubscribe'))), 'member/delete?id='.$member->getId()) ?>
<?php endif; ?>
</td>
<td>
<?php if (!$member->getIsLoginRejected()) : ?>
<?php echo link_to(image_tag('backend/icn_rejected.gif', array('alt' => __('Ban'))), 'member/reject?id='.$member->getId()) ?>
<?php else: ?>
<?php echo link_to(image_tag('backend/icn_permit.gif', array('alt' => __('Unban'))), 'member/reject?id='.$member->getId()) ?>
<?php endif; ?>
</td>
<td>
<?php echo link_to(image_tag('backend/icn_passwd.gif', array('alt' => __('Reissue password'))), 'member/reissuePassword?id='.$member->getId()) ?>
</td>
<td>
<?php echo link_to(image_tag('backend/icn_blacklist.gif', array('alt' => __('Add mobile UID to blacklist'))), 'member/blacklist?uid='.$member->getConfig('mobile_uid')) ?>
</td>
<td><?php echo $member->getId() ?></td>
<td><?php echo $member->getName() ?></td>
<td><?php if ($member->getInviteMember()) : ?><?php echo $member->getInviteMember()->getName() ?><?php endif; ?></td>
<td><?php if ($member->getLastLoginTime()) : ?><?php echo date('y-m-d<b\r />H:i:s', $member->getLastLoginTime()) ?><?php endif; ?></td>
<?php foreach ($profiles as $profile) : ?>
<?php if ($profile->isPreset()): ?>
<td><?php echo __((string)$member->getProfile($profile->getName())); ?></td>
<?php else: ?>
<td><?php echo $member->getProfile($profile->getName()); ?></td>
<?php endif; ?>
<?php endforeach; ?>
<td><?php echo $member->getConfig('pc_address') ?></td>
<td><?php echo $member->getConfig('mobile_address') ?></td>
<td><?php echo $member->getConfig('mobile_uid') ?></td>
</tr>
<?php endforeach; ?>

</table>
<?php endif; ?>
