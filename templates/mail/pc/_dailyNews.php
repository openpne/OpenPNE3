<?php echo __('━━ %1% ━━━━━━━━━━━━━━━━', array('%1%' => sfConfig::get('op_base_url'))) ?>


<?php echo $op_config['sns_name'] ?>


<?php echo __('Today News') ?>  [<?php echo date('Y. m. d/D', $today) ?>]

<?php echo __('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━') ?>


<?php echo __('Hi, %1%!', array('%1%' => $member->name)) ?>


<?php echo __('This is daily news in %1%.', array('%1%' => $op_config['sns_name'])) ?>


<?php foreach ($gadgets as $item): ?>
<?php include_component($item['component']['module'], $item['component']['action'], $item); ?>

<?php endforeach; ?>
