<?php
$options = array(
  'title' => __('プロフィール編集'),
  'url' => 'member/editProfile',
);
op_include_form('profileForm', array($memberForm, $profileForm), $options)
?>
