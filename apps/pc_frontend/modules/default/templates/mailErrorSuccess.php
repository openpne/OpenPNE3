<?php
$options = array(
  'title' => __('Errors'),
);
op_include_box('mailError', __('Couldn\'t send E-mail. Please retry or contact to administrator.'), $options);
?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>

