<?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?>
<ul>
<?php foreach ($pager->getResults() as $member) : ?>
<li><?php echo link_to($member->getName(), 'member/profile?id=' . $member->getId()); ?></li>
<?php endforeach; ?>
</ul>
<?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?>
