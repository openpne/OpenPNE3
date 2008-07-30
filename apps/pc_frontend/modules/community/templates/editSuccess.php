<?php if ($form->isNew()) : ?>
<form action="<?php echo url_for('community/edit') ?>" method="post">
<?php else : ?>
<form action="<?php echo url_for('community/edit?id=' . $community->getId()) ?>" method="post">
<?php endif; ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="登録" /></td>
</tr>
</table>
</form>
