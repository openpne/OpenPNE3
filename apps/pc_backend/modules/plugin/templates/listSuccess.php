<h2>プラグイン設定</h2>

<?php if ($plugins) : ?>
<?php echo $form->renderFormTag(url_for('plugin/list')); ?>
<table>
<tr>
<th><?php echo __('有効/無効') ?></th>
<th><?php echo __('プラグイン名') ?></th>
<th><?php echo __('バージョン') ?></th>
<th><?php echo __('プラグインの説明') ?></th>
</tr>
<?php foreach ($plugins as $plugin) : ?>
<tr>
<td><?php echo $form[$plugin->getName()]->render() ?></td>
<td><?php echo $form[$plugin->getName()]->renderLabel() ?></td>
<td><?php echo $plugin->getVersion() ?></td>
<td><?php echo $plugin->getSummary() ?></td>
</tr>
<?php endforeach; ?>
</table>
<input type="submit" value="<?php echo __('設定変更') ?>" />
</form>
<?php endif; ?>
