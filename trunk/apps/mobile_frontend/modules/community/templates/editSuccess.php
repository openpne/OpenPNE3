<?php include_page_title($community->getName(), 'ｺﾐｭﾆﾃｨの編集') ?>

<?php if ($form->isNew()) : ?>
<form action="<?php echo url_for('community/edit') ?>" method="post">
<?php else : ?>
<form action="<?php echo url_for('community/edit?id=' . $community->getId()) ?>" method="post">
<?php endif; ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="ｺﾐｭﾆﾃｨを編集する" /></td>
</tr>
</table>
</form>
