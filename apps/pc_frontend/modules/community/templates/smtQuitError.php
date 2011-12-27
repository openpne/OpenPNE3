<div class="row">
  <div class="gadget_header span12"><?php echo __('Errors') ?></div>
</div>

<div class="row">

<?php if ($isAdmin): ?>
<?php echo  __('The administrator doesn\'t leave the %community%.') ?>
<?php else: ?>
<?php echo  __('You haven\'t joined this %community% yet.') ?>
<?php endif; ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>

</div>
