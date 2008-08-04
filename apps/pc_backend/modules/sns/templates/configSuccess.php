<p>※「設定変更する」ボタンを押すと設定が反映されます。</p>
<form action="<?php echo url_for('sns/config') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="設定変更する" /></td>
</tr>
</table>
</form>
