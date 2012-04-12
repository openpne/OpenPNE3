<div class="row">
  <h3 class="span12"><?php echo __('Terms of service'); ?></h3>
</div>

<div class="row">
<?php echo nl2br($op_config['user_agreement']) ?>
</div>

<?php use_helper('Javascript') ?>
<div class="row">
<p><?php echo link_to_function(__('Back to previous page'), 'history.back()', array('class' => 'btn')) ?></p>
</div>
