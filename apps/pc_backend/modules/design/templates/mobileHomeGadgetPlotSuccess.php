<?php include_partial('plotHeader') ?>

<div id="plotBody">
<div id="container">

<?php include_partial('plotGadget', array('type' => 'mobileTop', 'gadgets' => $gadgets['mobileTop'], 'gadgetConfig' => $gadgetConfig)); ?>

<div class="fixedGadget">
<?php echo __('メニュー（変更不可）') ?>
</div>

<?php include_partial('plotGadget', array('type' => 'mobileContents', 'gadgets' => $gadgets['mobileContents'], 'gadgetConfig' => $gadgetConfig)); ?>

<div class="fixedGadget">
<?php echo __('設定変更（変更不可）') ?>
</div>

<?php include_partial('plotGadget', array('type' => 'mobileBottom', 'gadgets' => $gadgets['mobileBottom'], 'gadgetConfig' => $gadgetConfig)); ?>

<br style="clear:both;" />
</div>
</div>
