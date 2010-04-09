<?php include_partial('plotHeader') ?>
<?php use_helper('opUtil') ?>

<div id="plotBody" class="<?php echo $layoutPattern ?>">
<div id="container">

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutE') : ?>
<?php $typeName = op_get_gadget_type($type, 'top') ?>
<?php include_partial('plotGadget', array('type' => $typeName, 'gadgets' => $gadgets[$typeName], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php if ('mobile' == $type || 'mobileProfile' == $type): ?>
<div class="fixedGadget">
<?php echo __('メニュー（変更不可）') ?>
</div>
<?php endif; ?>

<?php if ($layoutPattern === 'layoutA' || $layoutPattern === 'layoutB') : ?>
<?php $typeName = op_get_gadget_type($type, 'sideMenu') ?>
<?php include_partial('plotGadget', array('type' => $typeName, 'gadgets' => $gadgets[$typeName], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<?php $typeName = op_get_gadget_type($type, 'contents') ?>
<?php include_partial('plotGadget', array('type' => $typeName, 'gadgets' => $gadgets[$typeName], 'gadgetConfig' => $gadgetConfig)); ?>

<?php if ('mobile' == $type): ?>
<div class="fixedGadget">
<?php echo __('設定変更（変更不可）') ?>
</div>
<?php endif; ?>

<?php if ($layoutPattern !== 'layoutD'): ?>
<?php $typeName = op_get_gadget_type($type, 'bottom') ?>
<?php include_partial('plotGadget', array('type' => $typeName, 'gadgets' => $gadgets[$typeName], 'gadgetConfig' => $gadgetConfig)); ?>
<?php endif; ?>

<br style="clear:both;" />
</div>
</div>
