<?php
$options = array(
  'title' => __('Invite a friend to %1%', array('%1%' => $op_config['sns_name'])),
);
op_include_box('inviteForm', __('Sent.'), $options);
?>
