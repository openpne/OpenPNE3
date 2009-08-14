<?php
$options = array(
  'title' => __('Edit Profile'),
  'url' => url_for('member/editProfile'),
  'mark_required_field' => true
);
op_include_form('profileForm', array($memberForm, $profileForm), $options)
?>
