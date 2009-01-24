<?php
$options = array(
  'title' => __('Invite a friend to %1%', array('%1%' => $op_config['sns_name'])),
  'url' => 'member/invite',
  'button' => __('Send'),
);
op_include_form('inviteForm', $form, $options);
?>
