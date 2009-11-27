<h2>プラグイン設定</h2>
<h3>スキン画像設定</h3>

<?php foreach ($forms as $image => $form): ?>
<form action="<?php echo url_for('opSkinClassicPlugin/skin?target='.$image) ?>" method="post" enctype="multipart/form-data">
<table>
<?php echo $form ?>
<tr>
<td colspan="2">
<input type="submit" value="<?php echo __('Save') ?>" />
</td>
</tr>
</table>
</form>
<?php endforeach; ?>

