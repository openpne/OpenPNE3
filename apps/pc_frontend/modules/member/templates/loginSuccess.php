<form action="<?php echo url_for('member/login') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="login" /></td>
</tr>
</table>
</form>

<?php echo link_to('新規登録', 'member/registerInput') ?>
