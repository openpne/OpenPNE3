個体識別番号を登録します。ﾊﾟｽﾜｰﾄﾞを入力して、設定ﾎﾞﾀﾝを押してください。
<form action="<?php echo url_for('member/configUID') ?>" method="post">
<table>
<?php echo $passwordForm ?>
<tr>
<td colspan="2"><input type="submit" value="設定" /></td>
</tr>
</table>
</form>
