<?php $options->setDefault('single', true) ?>

<div class="body sortHandle">
<?php include_customizes($id, 'bodyTop') ?>
<?php include_customizes('information', 'bodyTop') ?>
<?php echo $options->getRaw('body') ?>
<?php include_customizes('information', 'bodyBottom') ?>
<?php include_customizes($id, 'bodyBottom') ?>
</div>
