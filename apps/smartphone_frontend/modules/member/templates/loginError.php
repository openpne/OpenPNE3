<?php
$options = array(
  'title' => __('Errors'),
);
op_include_box('loginError', __('Failed in login.'), $options);
?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
