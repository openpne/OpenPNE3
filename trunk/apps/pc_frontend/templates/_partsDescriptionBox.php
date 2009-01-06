<?php include_customizes($id, 'before') ?>
<div id="<?php echo $id ?>" class="dparts descriptionBox"><div class="parts">
<?php include_customizes($id, 'top') ?>
<?php echo $sf_data->get('option')->getRaw('body') ?>
<?php include_customizes($id, 'bottom') ?>
</div></div>
<?php include_customizes($id, 'after') ?>
