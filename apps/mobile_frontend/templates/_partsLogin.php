<?php include_customizes($id, 'before') ?>

<table id="<?php echo $id ?>" width="100%">
<tr><td bgcolor="#0D6DDF"><font color="#EEEEEE"><?php echo $form->getAuthMode() ?></font></td></tr>

<tr><td bgcolor="#EEEEFF">
<form action="<?php echo $link_to ?>" method="post"<?php if ($form->isUtn()): ?> utn<?php endif; ?>>
<?php echo $form ?>
<center>
<input type="submit" value="ログイン">
</center>
</form>

<?php if ($form->getAuthAdapter()->getAuthConfig('invite_mode') == 2 && opToolkit::isEnabledRegistration('mobile')): ?>
<?php echo link_to('新規登録', $form->getAuthAdapter()->getAuthConfig('self_invite_action')) ?>
<?php endif; ?>

<?php include_customizes($id, 'bottom') ?>
</td></tr>

</table>
<br>

<?php include_customizes($id, 'after') ?>
