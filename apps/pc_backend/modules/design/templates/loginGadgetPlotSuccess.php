<?php include_partial('plotHeader') ?>

<div id="plotBody" class="<?php echo $layoutPattern ?>">
<div id="container">

<?php if ($layoutPattern === 'layoutA') : ?>
<?php include_partial('plotGadget', array('type' => 'loginTop', 'gadgets' => $gadgets['loginTop'], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutB') : ?>
<?php include_partial('plotGadget', array('type' => 'loginSideMenu', 'gadgets' => $gadgets['loginSideMenu'], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php include_partial('plotGadget', array('type' => 'loginContents', 'gadgets' => $gadgets['loginContents'], 'gadgetConfig' => $gadgetConfig)); ?>

<?php include_partial('plotGadget', array('type' => 'loginBottom', 'gadgets' => $gadgets['loginBottom'], 'gadgetConfig' => $gadgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
