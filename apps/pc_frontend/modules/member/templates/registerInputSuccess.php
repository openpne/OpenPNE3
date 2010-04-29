<?php
$options = array(
  'title' => __('Member Registration'),
  'url'   => url_for('member/registerInput?token='.$token),
  'button' => __('Register'),
);
op_include_form('RegisterForm', $form, $options);
?>
