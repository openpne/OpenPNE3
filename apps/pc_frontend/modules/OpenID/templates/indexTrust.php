<?php
$body = "<p>".__('Do you wish to login to the following site?')."</p>\n"
      . "<p><code>".$info->trust_root."</code></p>\n"
      . "<form method=\"post\" action=\"".url_for('OpenID/trust')."\">\n"
      . "<input type=\"submit\" name=\"trust\" value=\"Continue\" />\n"
      . "<input type=\"submit\" name=\"permanent\" value=\"Continue (permanently)\" />\n"
      . "<input type=\"submit\" value=\"Cancel\" />\n"
      . "</form>";
op_include_box('trustConfirm', $body, array('title' => __('Login to the site supporting OpenID')));
?>
