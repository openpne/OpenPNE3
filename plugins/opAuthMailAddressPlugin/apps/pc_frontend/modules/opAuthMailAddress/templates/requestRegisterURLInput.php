<?php slot('_request_register_url_body') ?>
<?php echo __('Please input your e-mail address. Invitation for %1% will be sent to your e-mail address.', array('%1%' => $op_config['sns_name'])) ?>
<?php end_slot(); ?>

<?php echo op_include_form('requestRegisterURL', $form, array(
  'title' => __('Register'),
  'body' => get_slot('_request_register_url_body'),
)); ?>
