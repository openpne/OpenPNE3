<?php include_partial('plotHeader') ?>

<div id="plotBody">
<div id="container">

<?php include_partial('plotGadget', array('type' => 'mobileTop', 'gadgets' => $mobileTopGadgets, 'gadgetConfig' => $gadgetConfig)); ?>

<div class="fixedGadget">
<?php echo __('メニュー（変更不可）') ?>
</div>

<?php include_partial('plotGadget', array('type' => 'mobileContents', 'gadgets' => $mobileContentsGadgets, 'gadgetConfig' => $gadgetConfig)); ?>

<div class="fixedGadget">
<?php echo __('設定変更（変更不可）') ?>
</div>

<?php include_partial('plotGadget', array('type' => 'mobileBottom', 'gadgets' => $mobileBottomGadgets, 'gadgetConfig' => $gadgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
