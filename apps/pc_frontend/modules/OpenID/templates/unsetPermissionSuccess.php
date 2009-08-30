<?php
op_include_form('unsetPermissionConfirm', $form, array(
  'url' => url_for('OpenID/unsetPermission?id='.$log->id),
  'title' => __('Unset Permission'),
  'body' => __('Do you unset the permission of this service?'),
  'button' => __('Unset'),
));
?>
