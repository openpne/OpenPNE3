<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>
<h2>ホーム画面ウィジェット設定</h2>

<?php use_helper('opJavascript') ?>

<div>
<form id="widgetForm" action="<?php url_for('design/homeWidget') ?>" method="post">
<?php foreach ($topWidgets as $key => $topWidget) : ?>
<input class="topWidget" type="hidden" name="widget[top][<?php echo $key ?>]" value="<?php echo $topWidget->getId() ?>" />
<?php endforeach; ?>
<?php foreach ($sideMenuWidgets as $key => $sideMenuWidget) : ?>
<input class="sideMenuWidget" type="hidden" name="widget[sideMenu][<?php echo $key ?>]" value="<?php echo $sideMenuWidget->getId() ?>" />
<?php endforeach; ?>
<?php foreach ($contentsWidgets as $key => $contentsWidget) : ?>
<input class="contentsWidget" type="hidden" name="widget[contents][<?php echo $key ?>]" value="<?php echo $contentsWidget->getId() ?>" />
<?php endforeach; ?>
<input type="submit" value="<?php echo __('設定変更') ?>" />
</form>
</div>

<iframe src="<?php echo url_for('design/homeWidgetPlot') ?>" width="610" height="410">
</iframe>

<?php echo make_modal_box('modal', '<iframe width="400" height="400"></iframe>', 400, 400) ?>
