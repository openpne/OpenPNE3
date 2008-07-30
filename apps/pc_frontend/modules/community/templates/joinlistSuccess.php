<?php use_helper('Pagination'); ?>

<?php echo pager_navigation($pager, 'community/joinlist?page=%d&id=' . $sf_params->get('id')); ?>
<ul>
<?php foreach ($pager->getResults() as $community) : ?>
<li><?php echo link_to($community->getName(), 'community/home?id=' . $community->getId()); ?></li>
<?php endforeach; ?>
</ul>
<?php echo pager_navigation($pager, 'community/joinlist?page=%d&id=' . $sf_params->get('id')); ?>
