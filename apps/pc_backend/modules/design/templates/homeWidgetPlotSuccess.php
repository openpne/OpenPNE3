<?php include_partial('plotHeader') ?>

<div id="plotBody" class="<?php echo $layoutPattern ?>">
<div id="container">

<?php if ($layoutPattern === 'layoutA') : ?>
<?php include_partial('plotWidget', array('type' => 'top', 'widgets' => $topWidgets, 'widgetConfig' => $widgetConfig)); ?>
<?php endif; ?>

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutB') : ?>
<?php include_partial('plotWidget', array('type' => 'sideMenu', 'widgets' => $sideMenuWidgets, 'widgetConfig' => $widgetConfig)); ?>
<?php endif; ?>

<?php include_partial('plotWidget', array('type' => 'contents', 'widgets' => $contentsWidgets, 'widgetConfig' => $widgetConfig)); ?>

</div>
</div>
