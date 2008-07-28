<form action="<?php echo url_for('member/registerInput') ?>" method="post">
<table>
<?php echo $authForm ?>
<?php echo $profileForm ?>
<tr>
<td colspan="2"><input type="submit" value="register" /></td>
</tr>
</table>
</form>
