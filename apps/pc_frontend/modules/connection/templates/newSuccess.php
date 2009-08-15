<?php
op_include_form('registerConnectionForm', $form, array(
  'isMultipart' => true,
  'title' => __('アプリケーション登録'),
  'body' => '登録したいアプリケーションの情報を入力してください。',
  'url' => url_for('connection_create'),
));
?>
