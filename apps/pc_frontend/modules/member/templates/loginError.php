<?php
$options = array(
  'title' => __('エラー'),
);
op_include_box('loginError', __('ログインに失敗しました。'), $options);
?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
