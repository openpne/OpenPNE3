<?php include_customizes($id, 'before') ?>
<?php if ($options['border']): ?>
<div id="<?php echo $id ?>" class="dparts <?php echo $options['class'] ?>">
<div class="parts">
<?php else: ?>
<div class="parts <?php echo $options['class'] ?>">
<?php endif ?>

<?php if ($title) : ?>
<div class="partsHeading">
<?php include_customizes($id, 'headTop') ?>
<h3><?php echo $title ?></h3>
<?php include_customizes($id, 'headBottom') ?>
</div>
<?php endif; ?>

<div class="block">
<?php echo $sf_data->getRaw('block') ?>
</div>

</div></div>
<?php include_customizes($id, 'after') ?>
