<?php
$options = array(
  'title' => __('Change Settings'),
  'url' => url_for(sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type'))),
  'button' => __('Send'),
);
op_include_form('formConfigComplete', $form, $options);
?>
