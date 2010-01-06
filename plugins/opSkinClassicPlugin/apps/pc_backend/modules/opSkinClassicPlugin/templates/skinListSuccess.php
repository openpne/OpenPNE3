<h2>プラグイン設定</h2>
<h3>スキン画像設定</h3>

<?php foreach ($forms as $image => $form): ?>
<form action="<?php echo url_for('opSkinClassicPlugin/skin?target='.$image) ?>" method="post" enctype="multipart/form-data">
<table class="skin">
<?php foreach ($form as $field): ?>
<?php if (!$field->isHidden()): ?>
<tr><th><?php echo $field->renderLabel() ?></th></tr>
<tr>
<td><?php echo $field->render() ?></td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<tr>
<td colspan="2" class="submit">
<?php echo $form->renderHiddenFields(); ?>
<input type="submit" value="<?php echo __('Save') ?>" />
</td>
</tr>
</table>
</form>
<?php endforeach; ?>
<br class="clear" />

