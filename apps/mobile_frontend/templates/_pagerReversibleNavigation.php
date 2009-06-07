<?php
$options->setDefault('is_total', false);
$options->setDefault('link_options', array());

$options->setDefault('prev_text', __('Previous', array(), 'pager'));
$options->setDefault('next_text', __('Next', array(), 'pager'));
?>

<?php if ($options['is_total'] || $pager->haveToPaginate()): ?>
<center>
<?php if ($pager->hasOlderPage()): ?>
<?php echo op_link_to_for_pager($options['prev_text'], $sf_data->getRaw('internalUri'), $pager->getOlderPage(), array_merge(array('accesskey' => 4), $options->getRaw('link_options'))) ?>
<?php endif; ?>
<?php if ($options['is_total']): ?>
<?php op_include_pager_total($pager) ?>
<?php endif; ?>
<?php if ($pager->hasNewerPage()): ?>
<?php if ($pager->hasOlderPage()): ?>&nbsp;<?php endif; ?>
<?php echo op_link_to_for_pager($options['next_text'], $sf_data->getRaw('internalUri'), $pager->getNewerPage(), array_merge(array('accesskey' => 6), $options->getRaw('link_options'))) ?>
<?php endif; ?>
</center>
<?php endif; ?>
