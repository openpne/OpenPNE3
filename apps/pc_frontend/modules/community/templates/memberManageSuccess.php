<?php op_include_pager_navigation($pager, 'community/memberManage?page=%d&id='.$sf_params->get('id')); ?>
<ul>
<?php foreach ($pager->getResults() as $member) : ?>
<li>
<?php echo link_to($member->getName(), 'member/profile?id='.$member->getId()); ?>
<?php if (!$community->isAdmin($member->getId())) : ?>
&nbsp;
<?php echo link_to(__('Drop this member'), 'community/dropMember?id='.$community->getId().'&member_id='.$member->getId()) ?>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<?php op_include_pager_navigation($pager, 'community/memberManage?page=%d&id='.$sf_params->get('id')); ?>
