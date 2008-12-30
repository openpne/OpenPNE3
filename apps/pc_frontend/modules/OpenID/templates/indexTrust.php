<?php
$body = "<p>".__('Do you wish to login to the following site?')."</p>\n"
      . "<p><code>".$info->trust_root."</code></p>\n"
      . "<form method=\"post\" action=\"".url_for('OpenID/trust')."\">\n"
      . "<input type=\"submit\" name=\"trust\" value=\"Continue\" />\n"
      . "<input type=\"submit\" value=\"Cancel\" />\n"
      . "</form>";
include_box('trustConfirm', 'Login to the site supporting OpenID', $body);
?>
