<?php
$options->setDefault('is_total', true);
$options->setDefault('link_options' ,array());

$options->setDefault('prev_text', __('Previous', array(), 'pager'));
$options->setDefault('next_text', __('Next', array(), 'pager'));
?>

<?php if ($options['is_total'] || $pager->haveToPaginate()): ?>
<div class="pagerRelative">
<?php if ($pager->getPreviousPage() != $pager->getPage()): ?>
<p class="prev"><?php echo op_link_to_for_pager($options['prev_text'], $sf_data->getRaw('internalUri'), $pager->getPreviousPage(), $options->getRaw('link_options')) ?></p>
<?php endif; ?>
<?php if ($options['is_total']): ?>
<p class="number"><?php op_include_pager_total($pager) ?></p>
<?php endif; ?>
<?php if ($pager->getNextPage() != $pager->getPage()): ?>
<p class="next"><?php echo op_link_to_for_pager($options['next_text'], $sf_data->getRaw('internalUri'), $pager->getNextPage(), $options->getRaw('link_options')) ?></p>
<?php endif; ?>
</div>
<?php endif; ?>
