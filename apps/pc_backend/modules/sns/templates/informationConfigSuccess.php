<h2>PC版ホームのお知らせ</h2>
<form action="<?php echo url_for('sns/informationConfig') ?>" method="post">
<table>
<?php echo $form['information']->render() ?>
<tr>
<td colspan="2"><input type="submit" value="設定変更する" /></td>
</tr>
</table>
</form>
