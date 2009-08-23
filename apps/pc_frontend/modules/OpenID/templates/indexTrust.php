<?php slot('_yes_form'); ?>
<input type="hidden" name="trust" value="1" />
<p>
<input type="checkbox" name="permanent" value="1" />
<?php echo __('Automatically login to %1% from now on', array('%1%' => parse_url($info->trust_root, PHP_URL_HOST))) ?>
</p>
<?php end_slot(); ?>

<?php slot('_body'); ?>
<p><?php echo __('Do you wish to login to the following site using your %1% ID?', array('%1%' => $op_config['sns_name'])) ?></p>
<p><code><?php echo $info->trust_root ?></code></p>
<?php end_slot(); ?>

<?php
op_include_parts('consentForm', 'trustConfirm', array(
  'title'        => __('Login to %1% using your %2% ID', array(
    '%1%' => parse_url($info->trust_root, PHP_URL_HOST),
    '%2%' => $op_config['sns_name'],
  )),
  'body'         => get_slot('_body'),
  'yes_form'     => get_slot('_yes_form'),
  'consent_from' => $op_config['sns_name'],
  'consent_to'   => parse_url($info->trust_root, PHP_URL_HOST),
  'yes_url'      => url_for('OpenID/trust'),
  'no_url'       => url_for('OpenID/trust'),
));
?>
