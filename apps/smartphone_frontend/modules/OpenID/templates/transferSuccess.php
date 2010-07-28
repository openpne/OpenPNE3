<?php use_helper('Javascript'); ?>

<?php slot('_body'); ?>
<p><?php echo __('This page will automatically redirect soon.') ?></p>
<p><?php echo __('If this does not work for any reason use the button below:') ?></p>
<?php echo $sf_data->getRaw('form') ?>
<?php end_slot(); ?>

<?php echo op_include_box('transfer', get_slot('_body')); ?>

<?php echo javascript_tag('document.getElementById("trans").submit();');
