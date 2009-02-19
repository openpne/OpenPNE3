<?php include_partial('plotHeader') ?>

<div id="plotBody" class="<?php echo $layoutPattern ?>">
<div id="container">

<?php if ($layoutPattern === 'layoutA') : ?>
<?php include_partial('plotGadget', array('type' => 'loginTop', 'gadgets' => $topGadgets, 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutB') : ?>
<?php include_partial('plotGadget', array('type' => 'loginSideMenu', 'gadgets' => $sideMenuGadgets, 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php include_partial('plotGadget', array('type' => 'loginContents', 'gadgets' => $contentsGadgets, 'gadgetConfig' => $gadgetConfig)); ?>

<?php include_partial('plotGadget', array('type' => 'loginBottom', 'gadgets' => $bottomGadgets, 'gadgetConfig' => $gadgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
