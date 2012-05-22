<?php if (!$mobileAddressPre): ?>
<?php echo __('Can access this registration URL with pc only.') ?>
<?php else: ?>
<?php op_include_parts('ButtonBox', 'opAuthMailAddressPluginRegisterBox', array(
  'title'  => __('Registration with your e-mail address'),
  'body'   => __('You can go to the registration page by clicking the button below.'),
  'button' => __('Go to the registration page'),
  'url'    => url_for($sf_user->getRegisterInputAction()),
  'method' => 'get',
)) ?>
<?php endif; ?>
