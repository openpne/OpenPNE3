<?php
$options = array(
  'title' => __('友人を%1%に招待する', array('%1%' => $op_config['sns_name'])),
);
op_include_box('inviteForm', __('招待が許可されていません。'), $options);
?>
