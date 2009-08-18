<?php
$options = array(
  'title' => __('Edit Profile'),
  'url' => url_for('member/editProfile'),
);
op_include_form('profileForm', array($memberForm, $profileForm), $options)
?>
