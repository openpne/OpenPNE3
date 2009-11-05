<?php include_customizes($id, 'before') ?>

<?php
$class = '';
if ($name) $class .= ' '.$name;
if (!empty($options['class'])) $class .= ' '.$options['class'];
?>
<?php if (empty($options['single'])): ?>
<div id="<?php echo $id ?>" class="dparts<?php echo $class ?>">
<div class="parts">
<?php else: ?>
<div id="<?php echo $id ?>" class="parts<?php echo $class ?>">
<?php endif; ?>

<?php if (isset($options['title'])): ?>
<div class="partsHeading"><h3><?php echo $options->getRaw('title') ?></h3></div>
<?php endif; ?>

<?php if (isset($options['partsInfo'])): ?>
<div class="partsInfo">
<div class="body">
<?php echo $options->getRaw('partsInfo') ?>
</div>
</div>
<?php endif; ?>

<?php include_customizes($id, 'top') ?>

<?php echo $sf_data->getRaw('op_content') ?>

<?php include_customizes($id, 'bottom') ?>

<?php if (isset($options['moreInfo'])): ?>
<div class="moreInfo">
<ul class="moreInfo">
<?php foreach ($options['moreInfo'] as $key => $value): ?>
<li><?php echo $options['moreInfo']->getRaw($key) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

</div><!-- parts -->
<?php if (empty($options['single'])): ?>
</div><!-- dparts -->
<?php endif; ?>

<?php include_customizes($id, 'after') ?>
