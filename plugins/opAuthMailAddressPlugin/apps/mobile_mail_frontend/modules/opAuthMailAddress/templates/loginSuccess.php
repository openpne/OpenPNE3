<?php $sf_context->getResponse()->setTitle(__('[%1%]Information of login page', array('%1%' => $op_config['sns_name']))); ?>
<?php echo __('Please access the following URL to login.') ?>

<?php $_SERVER['SCRIPT_NAME'] = '/index.php' ?>
<?php echo sfConfig::get('op_base_url').app_url_for('mobile_frontend', '@homepage', false) ?>
