<?php op_include_box('error', __('You can not delete your account.')); ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('Back to previous page'), 'history.back()') ?></p>
