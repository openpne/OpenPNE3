<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>
<h2>携帯版ホーム画面ウィジェット設定</h2>

<?php use_helper('opJavascript') ?>

<div>
<form id="widgetForm" action="<?php url_for('design/homeWidget') ?>" method="post">
<?php foreach ($mobileTopWidgets as $key => $mobileTopWidget) : ?>
<input class="mobileTopWidget" type="hidden" name="widget[mobileTop][<?php echo $key ?>]" value="<?php echo $mobileTopWidget->getId() ?>" />
<?php endforeach; ?>
<?php foreach ($mobileContentsWidgets as $key => $mobileContentsWidget) : ?>
<input class="mobileContentsWidget" type="hidden" name="widget[mobileContents][<?php echo $key ?>]" value="<?php echo $mobileContentsWidget->getId() ?>" />
<?php endforeach; ?>
<?php foreach ($mobileBottomWidgets as $key => $mobileBottomWidget) : ?>
<input class="mobileBottomWidget" type="hidden" name="widget[mobileBottom][<?php echo $key ?>]" value="<?php echo $mobileBottomWidget->getId() ?>" />
<?php endforeach; ?>
<?php echo $sortForm->renderHiddenFields(); ?>
<?php echo $addForm->renderHiddenFields(); ?>
<input type="submit" value="<?php echo __('設定変更') ?>" />
</form>
</div>

<iframe src="<?php echo url_for('design/mobileHomeWidgetPlot') ?>" width="610" height="410">
</iframe>

<?php echo make_modal_box('modal', '<iframe width="400" height="400"></iframe>', 400, 400) ?>
