<?php slot('_body'); ?>
<p><?php echo __('アプリケーションのアクセスを拒否しました。'); ?></p>
<?php end_slot(); ?>

<?php
op_include_box('rejectVerifierToken', get_slot('_body'), array(
  'title'    => __('アプリケーション許可設定'),
))
?>
