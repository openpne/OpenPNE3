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

<div class="description parts">
<h3><?php echo __('注意') ?></h3>
<p><?php echo __('プラグインを無効にした際に以下のような問題が発生する場合がありますのでご注意ください。') ?></p>
<p><?php echo __('例') ?></p>
<ul>
<li><?php echo __('退会処理がエラーとなり退会できない') ?></li>
<li><?php echo __('%Community%削除処理がエラーとなり削除できない') ?></li>
<li><?php echo __('プロフィール画像削除がエラーとなり削除できない') ?></li>
<li><?php echo __('%friend%に関連するデータを持つプラグインが無効な時に%friend%を削除すると、再有効化後に不要なデータが削除されずに残ってしまう') ?></li>
</ul>
<p><?php echo __('問題がある場合はプラグインを有効にしておくか、無効にするのではなく削除してください。') ?></p>
</div>

<div class="description parts">
<h2><?php echo __('プラグインの追加・削除') ?></h2>
<p><?php echo __('プラグインは管理画面上から追加・削除することはできません。') ?></p>
<p><?php echo __('プラグインインストール手順書に従ってサーバー上から操作をおこなってください。') ?></p>
</div>
