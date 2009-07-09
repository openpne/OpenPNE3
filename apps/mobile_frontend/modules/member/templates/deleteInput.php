<?php op_mobile_page_title(__('Delete your %1% account', array('%1%' => $op_config['sns_name']))) ?>
<?php echo __('Do you delete your %1% account?', array('%1%' => $op_config['sns_name'])) ?><br>
<?php echo __('Please input your password if you want to delete your account.') ?>

<?php op_include_form('deleteForm', $form, array(
  'url'    => url_for('member/delete'),
  'button' => __('Send'),
  'align'  => 'center'
)) ?>
