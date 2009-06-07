<?php
$options->setDefault('is_total', true);
$options->setDefault('link_options', array());

$options->setDefault('prev_text', __('Previous', array(), 'pager'));
$options->setDefault('next_text', __('Next', array(), 'pager'));
?>

<?php if ($options['is_total'] || $pager->haveToPaginate()): ?>
<?php if ($pager->hasOlderPage()): ?>
<?php echo op_link_to_for_pager($options['prev_text'], $sf_data->getRaw('internalUri'), $pager->getOlderPage(), $options->getRaw('link_options')) ?>&nbsp;
<?php endif; ?>
<?php if ($options['is_total']): ?>
<?php op_include_pager_total($pager) ?>
<?php endif; ?>
<?php if ($pager->hasNewerPage()): ?>
&nbsp;<?php echo op_link_to_for_pager($options['next_text'], $sf_data->getRaw('internalUri'), $pager->getNewerPage(), $options->getRaw('link_options')) ?>
<?php endif; ?>
<?php endif; ?>
