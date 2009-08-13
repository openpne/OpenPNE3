<h2><?php echo __('アプリケーション編集') ?></h2>

<?php echo $form->renderFormTag(url_for('connection/edit?id='.$consumer->id)) ?>
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Edit') ?>" /></td>
</tr>
</table>
</form>
