<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo $subtitle.'レイアウト設定' ?></h2>

<p><?php echo __('特定ページのレイアウトパターンを設定できます。ここで設定したレイアウトに従って、ガジェットを配置することができます。') ?></p>

<ul>
<?php foreach ($configs as $key => $config): ?>
<li><?php echo link_to($config['name'].'レイアウト設定', 'design/layout?type='.$key) ?></li>
<?php endforeach; ?>
</ul>

<?php echo $form->renderFormTag(url_for('design/layout?type='.$sf_request->getParameter('type', 'gadget'))) ?>
<p><input type="submit" value="<?php echo __('設定変更') ?>" /></p>
<?php echo $form['layout']->render() ?>
<?php echo $form->renderHiddenFields() ?>
<?php if ($sf_request->getParameter('login')) : ?>
<input type="hidden" name="login" value="1" />
<?php endif; ?>
</form>
