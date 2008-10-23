<?php include_customizes($id, 'before') ?>

<table id="<?php echo $id ?>" width="100%">
<tr><td bgcolor="#0D6DDF"><font color="#EEEEEE"><?php echo $form->getAuthMode() ?></font></td></tr>

<tr><td bgcolor="#EEEEFF">
<form action="<?php echo $link_to ?>" method="post"<?php if($form->isUtn()): ?> utn<?php endif; ?>>
<?php foreach ($form as $key => $row) : ?>
<?php if (!$row->isHidden()) : ?>
<?php echo $row->renderLabel(); ?><br>
<?php echo $row->render(); ?><br>
<?php endif; ?>
<?php endforeach; ?>
<center>
<input type="submit" value="ログイン">
</center>
</form>

<?php include_customizes($id, 'bottom') ?>
</td></tr>

</table>
<br>

<?php include_customizes($id, 'after') ?>
