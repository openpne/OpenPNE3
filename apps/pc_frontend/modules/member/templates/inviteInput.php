<?php
$options = array(
  'title' => __('友人を%1%に招待する', array('%1%' => $op_config['sns_name'])),
  'url' => 'member/invite',
  'button' => __('送信'),
);
op_include_form('inviteForm', $form, $options);
?>
