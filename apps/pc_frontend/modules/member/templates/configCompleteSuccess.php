<?php
$options = array(
  'title' => __('設定変更'),
  'url' => sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type')),
  'button' => __('送信'),
);
op_include_form('formConfigComplete', $form, $options);
?>
