管理用のアカウント名とパスワードを入力してください。
<form action="<?php echo url_for('default/login') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="ログイン" /></td>
</tr>
</table>
</form>
