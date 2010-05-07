<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, '@community_memberManage?page=%d&id='.$sf_params->get('id')); ?>
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
<td class="member"><?php echo op_link_to_member($member); ?></td>

<td class="drop">
<?php if (!($communityMember->hasPosition(array('admin', 'sub_admin')) || $communityMember->getMemberId() === $sf_user->getMemberId())) : ?>
<?php echo link_to(__('Drop this member'), 'community/dropMember?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php else: ?>
&nbsp;
<?php endif; ?>
</td>

<?php if ($isAdmin): ?>
<td class="sub_admin_request">
<?php if (!$communityMember->hasPosition(array('admin', 'admin_confirm', 'sub_admin'))) : ?>
<?php if ($communityMember->hasPosition('sub_admin_confirm')): ?>
<?php echo __("You are requesting this %community%'s sub-administrator to this member now.") ?>
<?php else: ?>
<?php echo link_to(__("Request this %community%'s sub-administrator to this member"), 'community/subAdminRequest?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php endif; ?>
<?php elseif ($communityMember->hasPosition('sub_admin')): ?>
<?php echo link_to(__("Demotion from this %community%'s sub-administrator"), 'community/removeSubAdmin?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php else: ?>
&nbsp;
<?php endif; ?>
</td>

<td class="admin_request">
<?php if (!$communityMember->hasPosition('admin')) : ?>
<?php if ($communityMember->hasPosition('admin_confirm')): ?>
<?php echo __("You are taking over this %community%'s administrator to this member now.") ?>
<?php else: ?>
<?php echo link_to(__("Take over this %community%'s administrator to this member"), 'community/changeAdminRequest?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php endif; ?>
<?php else: ?>
&nbsp;
<?php endif; ?>
</td>
<?php endif; ?>

<?php include_customizes('id_member', 'after', $customizeOption) ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php include_slot('pager') ?>
</div>
