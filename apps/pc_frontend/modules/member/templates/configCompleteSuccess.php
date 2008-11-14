<?php include_box('formConfigComplete', '設定変更', '', array(
  'form' => array($form),
  'url' => sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type')),
  'button' => '送信',
));

?>
