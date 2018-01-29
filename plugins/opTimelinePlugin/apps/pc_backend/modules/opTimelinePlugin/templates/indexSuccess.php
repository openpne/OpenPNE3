<h2>タイムライン設定</h2>

<?php if (count($form) - 1): ?>
<form action="<?php echo url_for('opTimelinePlugin/index') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('Save') ?>" /></td>
</tr>
</table>
</form>
<?php else: ?>
<p>設定可能な項目がありません</p>
<?php endif; ?>

