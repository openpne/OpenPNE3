<?php if ($pager->haveToPaginate()) : ?>
<?php echo link_to('< 前', 'member/list?page=' . $pager->getPreviousPage()) ?>&nbsp;
<?php echo link_to('次 >', 'member/list?page=' . $pager->getNextPage()) ?>
<?php endif; ?>

<ul>
<?php foreach ($pager->getResults() as $member) : ?>
<li><?php echo $member->getProfile('nickname'); ?></li>
<?php endforeach; ?>
</ul>
