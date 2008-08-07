<?php foreach ($forms as $category => $form) : ?>
<form action="<?php echo url_for('member/config?category=' . $category) ?>" method="post">
<table id="<?php echo $category ?>">
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="変更" /></td>
</tr>
</table>
</form>
<?php endforeach; ?>
