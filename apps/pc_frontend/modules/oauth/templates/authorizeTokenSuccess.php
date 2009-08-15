<?php slot('_body'); ?>
<p><?php echo __('アプリケーションの指示に従って、以下のコードを入力してください。'); ?></p>
<input type="text" readonly="readonly" value="<?php echo $information->verifier; ?>" />
<?php end_slot(); ?>

<?php
op_include_box('showVerifierToken', get_slot('_body'), array(
  'title'    => __('アプリケーション許可設定'),
))
?>
