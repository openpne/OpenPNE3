<?php
$options->setDefault('is_total', true);
$options->setDefault('query_string', '');

$options->setDefault('prev_text', __('Previous', array(), 'pager'));
$options->setDefault('next_text', __('Next', array(), 'pager'));
?>

<?php if ($options['is_total'] || $pager->haveToPaginate()): ?>
<?php if ($pager->getPreviousPage() != $pager->getPage()): ?>
<?php echo link_to($options['prev_text'], sprintf($sf_data->getRaw('link_to'), $pager->getPreviousPage()), array('query_string' => $options['query_string'])) ?>&nbsp;
<?php endif; ?>
<?php if ($options['is_total']): ?>
<?php op_include_pager_total($pager) ?>
<?php endif; ?>
<?php if ($pager->getNextPage() != $pager->getPage()): ?>
&nbsp;<?php echo link_to($options['next_text'], sprintf($sf_data->getRaw('link_to'), $pager->getNextPage()), array('query_string' => $options['query_string'])) ?>
<?php endif; ?>
<?php endif; ?>
