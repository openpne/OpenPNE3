<p><?php echo __('You can\'t access this page.') ?></p>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('Back to previous page'), 'history.back()') ?></p>
