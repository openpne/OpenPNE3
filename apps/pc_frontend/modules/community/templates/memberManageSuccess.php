<?php echo pager_navigation($pager, 'community/memberList?page=%d&id='.$sf_params->get('id')); ?>
<ul>
<?php foreach ($pager->getResults() as $member) : ?>
<li>
<?php echo link_to($member->getName(), 'member/profile?id='.$member->getId()); ?>
<?php $communityMembers = $member->getCommunityMembers(); ?>
<?php if ($communityMembers[0]->getPosition() !== 'admin') : ?>
&nbsp;
<?php echo link_to(__('Drop this member'), 'community/dropMember?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<?php echo pager_navigation($pager, 'community/memberList?page=%d&id='.$sf_params->get('id')); ?>
