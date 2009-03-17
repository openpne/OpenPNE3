<?php
$options->setDefault('is_total', false);
$options->setDefault('query_string', '');

$options->setDefault('prev_text', __('Previous', array(), 'pager'));
$options->setDefault('next_text', __('Next', array(), 'pager'));
?>

<?php if ($options['is_total'] || $pager->haveToPaginate()): ?>
<?php if ($pager->hasOlderPage()): ?>
<?php echo link_to($options['prev_text'], sprintf($sf_data->getRaw('link_to'), $pager->getOlderPage()), array('query_string' => $options['query_string'])) ?>&nbsp;
<?php endif; ?>
<?php if ($options['is_total']): ?>
<?php op_include_pager_total($pager) ?>
<?php endif; ?>
<?php if ($pager->hasNewerPage()): ?>
&nbsp;<?php echo link_to($options['next_text'], sprintf($sf_data->getRaw('link_to'), $pager->getNewerPage()), array('query_string' => $options['query_string'])) ?>
<?php endif; ?>
<?php endif; ?>
