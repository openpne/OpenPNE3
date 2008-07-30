<?php use_helper('Pagination'); ?>

<?php echo pager_navigation($pager, 'community/memberList?page=%d&id=' . $sf_params->get('id')); ?>
<ul>
<?php foreach ($pager->getResults() as $member) : ?>
<li><?php echo link_to($member->getName(), 'member/profile?id=' . $member->getId()); ?></li>
<?php endforeach; ?>
</ul>
<?php echo pager_navigation($pager, 'community/memberList?page=%d' . $sf_params->get('id')); ?>
