<?php if (!isset($is_total)) $is_total = true ?>

<?php if ($is_total || $pager->haveToPaginate()): ?>
<div class="pagerRelative">
<?php if ($pager->getPreviousPage() != $pager->getPage()): ?><p class="prev"><?php echo link_to(__('Previous', array(), 'pager'), sprintf($sf_data->getRaw('link_to'), $pager->getPreviousPage())) ?></p><?php endif; ?>
<?php if ($is_total): ?><p class="number"><?php echo __('%first% - %last% of %total%', array('%first%' => $pager->getFirstIndice(), '%last%' => $pager->getLastIndice(), '%total%' => $pager->getNbResults()), 'pager') ?></p><?php endif; ?>
<?php if ($pager->getNextPage() != $pager->getPage()): ?><p class="next"><?php echo link_to(__('Next', array(), 'pager'), sprintf($sf_data->getRaw('link_to'), $pager->getNextPage())) ?></p><?php endif; ?>
</div>
<?php endif; ?>
