<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<?php if ('auth' === $type): ?>
<h2><?php echo __('認証プラグイン設定') ?></h2>

<p><?php echo __('認証プラグインは少なくともどれか一つが「有効」になっている必要があります。') ?></p>

<?php elseif ('skin' === $type): ?>
<h2><?php echo __('スキンプラグイン設定') ?></h2>

<p><?php echo __('スキンプラグインはどれか一つのみが「有効」になっている必要があります。') ?></p>

<?php else: ?>
<h2><?php echo __('アプリケーションプラグイン設定') ?></h2>
<?php endif; ?>

<?php if ($plugins) : ?>
<?php echo $form->renderFormTag(url_for('plugin/list?type='.$type)); ?>
<table>
<tr>
<th><?php echo __('有効/無効') ?></th>
<th><?php echo __('プラグイン名') ?></th>
<th><?php echo __('バージョン') ?></th>
<th><?php echo __('プラグインの説明') ?></th>
<th><?php echo __('操作') ?></th>
</tr>
<?php echo $form['plugin']->render() ?>
<tr>
<td colspan="5">
<?php echo $form->renderHiddenFields() ?>
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
