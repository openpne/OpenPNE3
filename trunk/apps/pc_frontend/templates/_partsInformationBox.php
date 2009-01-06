<?php include_customizes($id, 'before') ?>
<div id="<?php echo $id ?>" class="parts informationBox">
<div class="body">
<?php include_customizes($id, 'top') ?>
<?php echo $sf_data->getRaw('body') ?>
<?php include_customizes($id, 'bottom') ?>
</div>
</div>
<?php include_customizes($id, 'after') ?>
