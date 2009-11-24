<h2>プラグイン設定</h2>

<h3>プリセット設定変更</h3>
<p>あらかじめ用意されているスキン画像、配色設定を設定します。</p>
<p>※この設定をおこなうと、既に設定済みのスキン画像、配色がリセットされます。</p>

<form action="<?php echo url_for('opSkinClassicPlugin/preset') ?>" method="post">
<table>
<?php echo $presetForm ?>
<tr>
<td colspan="2">
<input type="submit" value="<?php echo __('Save') ?>" />
</td>
</tr>
</table>
</form>

<h3>配色設定変更</h3>

<form action="<?php echo url_for('opSkinClassicPlugin/color') ?>" method="post">
<table>
<?php echo $colorForm ?>
<tr>
<td colspan="2">
<input type="submit" value="<?php echo __('Save') ?>" />
</td>
</tr>
</table>
</form>

<h3>スキン画像設定</h3>
<p><?php echo link_to('スキン画像設定', 'opSkinClassicPlugin/skinList') ?></p>

