<?php include_customizes($id, 'before') ?>
<?php if ($option['border']): ?>
<div id="<?php echo $id ?>" class="dparts <?php echo $option['class'] ?>">
<div class="parts">
<?php else: ?>
<div class="parts <?php echo $option['class'] ?>">
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
