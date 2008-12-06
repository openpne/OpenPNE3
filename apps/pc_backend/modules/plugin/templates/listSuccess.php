<h2><?php echo __('プラグイン設定') ?></h2>

<?php if ($plugins) : ?>
<?php echo $form->renderFormTag(url_for('plugin/list')); ?>
<table>
<tr>
<th><?php echo __('有効/無効') ?></th>
<th><?php echo __('プラグイン名') ?></th>
<th><?php echo __('バージョン') ?></th>
<th><?php echo __('プラグインの説明') ?></th>
<th><?php echo __('操作') ?></th>
</tr>
<?php foreach ($plugins as $plugin) : ?>
<tr>
<td><?php echo $form[$plugin->getName()]->render() ?></td>
<td><?php echo $form[$plugin->getName()]->renderLabel() ?></td>
<td><?php echo $plugin->getVersion() ?></td>
<td><?php echo $plugin->getSummary() ?></td>
<td><?php if ($plugin->hasBackend()) : ?><?php echo link_to(__('設定'), $plugin->getName().'/index') ?><?php endif; ?></td>
</tr>
<?php endforeach; ?>
<tr>
<td colspan="5">
<input type="submit" value="<?php echo __('設定変更') ?>" />
</td>
</tr>
</table>
</form>
<?php endif; ?>

<h2><?php echo __('プラグインの追加') ?></h2>

<p><?php echo __('プラグインはプラグイン配布ページからダウンロードすることができます。') ?></p>
<p><?php echo __('ダウンロードしたファイルを解凍し、サーバ上の plugins ディレクトリにアップロードすることでプラグインがインストールできます。') ?></p>
<p><?php echo __('また、 opPlugin:install コマンドを実行することでもインストール可能です。') ?></p>
