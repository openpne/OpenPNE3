<?php
$body = "<p>"
      . __('アプリケーション「%1%」がリソースへのアクセスを希望しています。', array('%1%' => $information->getConsumer()->getName()))
      ."</p>\n"
      . "<p>".__('許可しますか？')."</p>\n"
      . "<form method=\"post\" action=\"".url_for('@oauth_authorize_token')."\">\n"
      . "<input type=\"submit\" name=\"allow\" value=\"Continue\" />\n"
      . "<input type=\"submit\" value=\"Cancel\" />\n"
      . "</form>";
op_include_box('confirmBox', $body, array('title' => __('アプリケーションアクセス許可確認')));
?>
