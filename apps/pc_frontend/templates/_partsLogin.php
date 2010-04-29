<?php include_customizes($id, 'before') ?>

<div id="<?php echo $id ?>" class="loginForm">

<?php include_customizes($id, 'top') ?>

<form action="<?php echo $link_to ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2">
<?php if ($form->getAuthAdapter()->getAuthConfig('help_login_error_action')) : ?>
<p class="password_query"><?php echo link_to(__('Can not access your account?'), $form->getAuthAdapter()->getAuthConfig('help_login_error_action')); ?></p>
<?php endif; ?>
<input type="submit" class="input_submit" value="<?php echo __('Login') ?>" />
</td>
</tr>
</table>
</form>

<?php if ($form->getAuthAdapter()->getAuthConfig('invite_mode') == 2
  && opToolkit::isEnabledRegistration('pc')
  && $form->getAuthAdapter()->getAuthConfig('self_invite_action')) : ?>
<?php echo link_to(__('Register'), $form->getAuthAdapter()->getAuthConfig('self_invite_action')) ?>
<?php endif; ?>

<?php include_customizes($id, 'bottom') ?>

</div>

<?php include_customizes($id, 'after') ?>
