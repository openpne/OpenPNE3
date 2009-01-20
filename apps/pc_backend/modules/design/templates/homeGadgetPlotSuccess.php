<?php include_partial('plotHeader') ?>

<div id="plotBody" class="<?php echo $layoutPattern ?>">
<div id="container">

<?php if ($layoutPattern === 'layoutA') : ?>
<?php include_partial('plotGadget', array('type' => 'top', 'gadgets' => $topGadgets, 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutB') : ?>
<?php include_partial('plotGadget', array('type' => 'sideMenu', 'gadgets' => $sideMenuGadgets, 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php include_partial('plotGadget', array('type' => 'contents', 'gadgets' => $contentsGadgets, 'gadgetConfig' => $gadgetConfig)); ?>

<?php include_partial('plotGadget', array('type' => 'bottom', 'gadgets' => $bottomGadgets, 'gadgetConfig' => $gadgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
