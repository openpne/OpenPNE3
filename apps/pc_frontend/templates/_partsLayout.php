<?php include_customizes($id, 'before') ?>

<?php if (empty($options['single'])): ?>
<div id="<?php echo $id ?>" class="dparts<?php if ($name) echo ' '.$name ?>">
<div class="parts">
<?php else: ?>
<div id="<?php echo $id ?>" class="parts<?php if ($name) echo ' '.$name ?>">
<?php endif; ?>

<?php if (isset($options['title'])): ?>
<div class="partsHeading"><h3><?php echo $options['title'] ?></h3></div>
<?php endif; ?>

<?php include_customizes($id, 'top') ?>
<?php echo $sf_data->getRaw('op_content') ?>
<?php include_customizes($id, 'bottom') ?>

</div><!-- parts -->
<?php if (empty($options['single'])): ?>
</div><!-- dparts -->
<?php endif; ?>

<?php include_customizes($id, 'after') ?>
