<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2>
<?php if ($sf_request->getParameter('login')) : ?>
<?php echo __('ログイン画面レイアウト設定'); ?>
<?php else: ?>
<?php echo __('ホーム画面レイアウト設定') ?>
<?php endif; ?>
</h2>

<p><?php echo __('特定ページのレイアウトパターンを設定できます。ここで設定したレイアウトに従って、ガジェットを配置することができます。') ?></p>

<ul>
<li><?php echo link_to(__('ホーム画面レイアウト設定'), 'design/homeLayout') ?></li>
<li><?php echo link_to(__('ログイン画面レイアウト設定'), 'design/homeLayout?login=1') ?></li>
</ul>

<?php echo $form->renderFormTag(url_for('design/homeLayout')) ?>
<p><input type="submit" value="<?php echo __('設定変更') ?>" /></p>
<?php echo $form['layout']->render() ?>
<?php echo $form->renderHiddenFields() ?>
<?php if ($sf_request->getParameter('login')) : ?>
<input type="hidden" name="login" value="1" />
<?php endif; ?>
</form>
