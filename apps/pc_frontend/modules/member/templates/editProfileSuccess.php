<?php
$options = array(
  'title' => __('Edit Profile'),
  'url' => 'member/editProfile',
);
op_include_form('profileForm', array($memberForm, $profileForm), $options)
?>
