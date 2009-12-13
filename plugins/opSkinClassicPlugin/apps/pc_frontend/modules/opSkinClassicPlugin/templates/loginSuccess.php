<div id="container_login"><div class="w_screen">
<?php if ($form->getAuthAdapter()->getAuthConfig('invite_mode') == 2 && opToolkit::isEnabledRegistration('pc')) : ?>
<img src="<?php echo image_path(opSkinClassicConfig::get('skin_login_open_image')) ?>" class="bg" alt="" />
<?php else: ?>
<img src="<?php echo image_path(opSkinClassicConfig::get('skin_login_image')) ?>" class="bg" alt="" />
<?php endif; ?>

<form action="<?php echo url_for(sprintf('member/login?%s=%s', sfOpenPNEAuthForm::AUTH_MODE_FIELD_NAME, $form->getAuthMode())) ?>" method="post">
<?php foreach ($form as $name => $field): ?>
<?php echo $field->render() ?>
<?php endforeach; ?>
<input type="image" name="submit" src="<?php echo image_path('dummy.gif') ?>" id="button_login" alt="ログイン" />
<?php if ($form->getAuthAdapter()->getAuthConfig('invite_mode') == 2 && opToolkit::isEnabledRegistration('pc')) : ?>
<?php echo link_to('<img src="'.image_path('dummy.gif').'" alt="新規登録" />', $form->getAuthAdapter()->getAuthConfig('self_invite_action'), array('id' => 'button_new_regist')) ?>
<?php endif; ?>
<div class="msg lh_130">
<?php if ($form->getAuthAdapter()->getAuthConfig('help_login_error_action')) : ?>
<br />
<span class="password_query"><?php echo link_to(__('Can not access your account?'), $form->getAuthAdapter()->getAuthConfig('help_login_error_action')); ?></span>
<?php endif; ?>
</div>
</form>

<div class="footer">
<?php include_partial('global/footer') ?>
</div>
</div></div>
