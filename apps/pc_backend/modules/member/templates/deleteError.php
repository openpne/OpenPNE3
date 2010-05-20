<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Unsubscribe') ?></h2>

<p><?php echo __('Can\'t unsubscribe the member has ID = 1') ?></p>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('Return to previous page'), 'history.back()') ?></p>
