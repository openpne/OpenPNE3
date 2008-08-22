<p>メールアドレスを入力してください。</p>
<p>入力されたメールアドレス宛に <?php echo OpenPNEConfig::get('sns_name') ?> の招待状が送信されます。</p>
<form action="<?php echo url_for('pcAddress/requestRegisterURL') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td><input type="submit" value="送信" /></td>
</tr>
</table>
</form>
