<?php include_partial('plotHeader') ?>

<div id="plotBody" class="<?php echo $layoutPattern ?>">
<div id="container">

<?php if ($layoutPattern === 'layoutA') : ?>
<?php include_partial('plotGadget', array('type' => 'top', 'gadgets' => $gadgets['top'], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutB') : ?>
<?php include_partial('plotGadget', array('type' => 'sideMenu', 'gadgets' => $gadgets['sideMenu'], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php include_partial('plotGadget', array('type' => 'contents', 'gadgets' => $gadgets['contents'], 'gadgetConfig' => $gadgetConfig)); ?>

<?php include_partial('plotGadget', array('type' => 'bottom', 'gadgets' => $gadgets['bottom'], 'gadgetConfig' => $gadgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
