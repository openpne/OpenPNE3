<?php slot('submenu'); ?>
<?php include_partial('submenu'); ?>
<?php end_slot(); ?>

<h2><?php echo __('Send invitation message') ?></h2>

<p><?php echo __('Authentication plugin is not available.') ?></p>
<p><?php echo __('Please try this again after changing "Authentication plugin" settings in "Plugins" menu.') ?></p>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('Return to previous page'), 'history.back()') ?></p>
