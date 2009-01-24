<?php
$options = array(
  'title' => __('Invite a friend to %1%', array('%1%' => $op_config['sns_name'])),
);
op_include_box('inviteForm', __('招待が許可されていません。'), $options);
?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('Back to previous page'), 'history.back()') ?></p>
