<?php include_customizes($id, 'before') ?>

<div id="<?php echo $id ?>">

<?php include_customizes($id, 'top') ?>

<form action="<?php echo $link_to ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" class="input_submit" value="ログイン" /></td>
</tr>
</table>
</form>

<?php if ($form->getAuthAdapter()->getAuthConfig('invite_mode') == 2 && opToolkit::isEnabledRegistration('pc')) : ?>
<?php echo link_to(__('Register'), $form->getAuthAdapter()->getAuthConfig('self_invite_action')) ?>
<?php endif; ?>

<?php include_customizes($id, 'bottom') ?>

</div>

<?php include_customizes($id, 'after') ?>
