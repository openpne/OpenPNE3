<?php include_partial('plotHeader') ?>

<div id="plotBody">
<div id="container">

<?php include_partial('plotWidget', array('type' => 'mobileTop', 'widgets' => $mobileTopWidgets, 'widgetConfig' => $widgetConfig)); ?>

<div class="fixedWidget">
<?php echo __('メニュー（変更不可）') ?>
</div>

<?php include_partial('plotWidget', array('type' => 'mobileContents', 'widgets' => $mobileContentsWidgets, 'widgetConfig' => $widgetConfig)); ?>

<div class="fixedWidget">
<?php echo __('設定変更（変更不可）') ?>
</div>

<?php include_partial('plotWidget', array('type' => 'mobileBottom', 'widgets' => $mobileBottomWidgets, 'widgetConfig' => $widgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
