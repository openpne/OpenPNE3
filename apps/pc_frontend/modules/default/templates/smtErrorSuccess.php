<div class="row">
  <h3 class="span12"><?php echo __('You can\'t access this page.') ?></h3>
</div>

<?php use_helper('Javascript') ?>
<div class="row">
  <?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
</div>
