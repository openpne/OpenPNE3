<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, 'community/memberManage?page=%d&id='.$sf_params->get('id')); ?>
<?php end_slot(); ?>

<div class="parts">
<div class="partsHeading"><h3><?php echo __('Management member') ?></h3></div>
<?php include_slot('pager') ?>
<div class="item">
<table>
<tbody>
<?php foreach ($pager->getResults() as $member) : ?>
<?php 
$customizeOption = array('member' => $member, 'community' => $community);
$communityMember = Doctrine::getTable('CommunityMember')->retrieveByMemberIdAndCommunityId($member->getId(), $community->getId());
?>

<tr>
<?php include_customizes('id_member', 'before', $customizeOption) ?>
<td class="member"><?php echo link_to($member->getName(), 'member/profile?id='.$member->getId()); ?></td>

<td class="drop">
<?php if ('admin' !== $communityMember->getPosition()) : ?>
<?php echo link_to(__('Drop this member'), 'community/dropMember?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php else: ?>
&nbsp;
<?php endif; ?>
</td>

<td class="admin_request">
<?php if (!$communityMember->getPosition()) : ?>
<?php echo link_to(__("Take over this %community%'s administrator to this member"), 'community/changeAdminRequest?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php elseif ('admin_confirm' === $communityMember->getPosition()): ?>
<?php echo __("You are taking over this %community%'s administrator to this member now.") ?>
<?php else: ?>
&nbsp;
<?php endif; ?>
</td>

<?php include_customizes('id_member', 'after', $customizeOption) ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php include_slot('pager') ?>
</div>
