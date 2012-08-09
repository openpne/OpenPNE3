<?php if (!$addressPre): ?>
<?php
$options = array(
  'title' => __('Errors'),
);
op_include_box('registerError', __('Your e-mail address is not registered.'), $options);
?>
<?php else: ?>
<?php op_include_parts('ButtonBox', 'opAuthMailAddressPluginRegisterBox', array(
  'title'  => __('Registration with your e-mail address'),
  'body'   => __('You can go to the registration page by clicking the button below.'),
  'button' => __('Go to the registration page'),
  'url'    => url_for($sf_user->getRegisterInputAction()),
  'method' => 'get',
)) ?>
<?php endif; ?>
