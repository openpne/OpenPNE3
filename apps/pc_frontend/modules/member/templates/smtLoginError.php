<div class="row">
  <div class="gadget_header span12"><?php echo __('Error'); ?></div>
</div>

<?php echo __('Failed in login.'); ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
