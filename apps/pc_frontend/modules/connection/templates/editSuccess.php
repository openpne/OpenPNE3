<?php
op_include_form('registerConnectionForm', $form, array(
  'isMultipart' => true,
  'url' => url_for('connection_update', $consumer),
  'title' => __('アプリケーション編集'),
  'button' => __('Edit'),
));
?>
