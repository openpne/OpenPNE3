<?php op_mobile_page_title(__('Invite friends for %1%', array('%1%' => $op_config['sns_name']))) ?>

<?php op_include_form('inviteForm', $form, array(
  'url'    => url_for('member/invite'),
  'button' => __('Send'),
  'align'  => 'center',
)) ?>
