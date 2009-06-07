<?php
$options->setDefault('is_total', true);
$options->setDefault('link_options', array());

$options->setDefault('prev_text', __('Previous', array(), 'pager'));
$options->setDefault('next_text', __('Next', array(), 'pager'));
?>

<?php if ($options['is_total'] || $pager->haveToPaginate()): ?>
<?php if ($pager->getPreviousPage() != $pager->getPage()): ?>
<?php echo op_link_to_for_pager($options['prev_text'], $sf_data->getRaw('internalUri'), $pager->getPreviousPage(), $options->getRaw('link_options')) ?>&nbsp;
<?php endif; ?>
<?php if ($options['is_total']): ?>
<?php op_include_pager_total($pager) ?>
<?php endif; ?>
<?php if ($pager->getNextPage() != $pager->getPage()): ?>
&nbsp;<?php echo op_link_to_for_pager($options['next_text'], $sf_data->getRaw('internalUri'), $pager->getNextPage(), $options->getRaw('link_options')) ?>
<?php endif; ?>
<?php endif; ?>
