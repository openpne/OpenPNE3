<?php include_partial('plotHeader') ?>

<div id="plotBody" class="<?php echo $layoutPattern ?>">
<div id="container">

<?php if ($layoutPattern === 'layoutA') : ?>
<?php include_partial('plotGadget', array('type' => 'profileTop', 'gadgets' => $gadgets['profileTop'], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutB') : ?>
<?php include_partial('plotGadget', array('type' => 'profileSideMenu', 'gadgets' => $gadgets['profileSideMenu'], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php include_partial('plotGadget', array('type' => 'profileContents', 'gadgets' => $gadgets['profileContents'], 'gadgetConfig' => $gadgetConfig)); ?>

<?php include_partial('plotGadget', array('type' => 'profileBottom', 'gadgets' => $gadgets['profileBottom'], 'gadgetConfig' => $gadgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
