<?php op_mobile_page_title(__('Settings')) ?>

<?php op_include_form('configComplateForm', $form, array(
  'url'    => url_for(sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type'))),
  'button' => __('Send'),
  'align'  => 'center'
)) ?>
