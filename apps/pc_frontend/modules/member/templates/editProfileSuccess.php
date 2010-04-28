<?php
$options = array(
  'title' => __('Edit Profile'),
  'url' => url_for('@member_editProfile'),
);
op_include_form('profileForm', array($memberForm, $profileForm), $options)
?>
