<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション登録') ?></h2>

<p>登録したいアプリケーションの情報を入力してください。</p>

<?php echo $form->renderFormTag(url_for('connection_create')) ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('登録') ?>" /></td>
</tr>
</table>
</form>
