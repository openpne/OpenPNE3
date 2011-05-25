<?php if ($categoryName): ?>
<?php op_mobile_page_title(__('Settings'), __($categoryCaptions[$categoryName])) ?>
<?php else: ?>
<?php op_mobile_page_title(__('Settings')) ?>
<?php endif; ?>

<?php if ($categoryName): ?>
<?php op_include_form('configForm', $form, array(
  'url'    => url_for('member/config?category='.$categoryName),
  'align'  => 'center',
  'button' => __('Save')
)) ?>
<?php if ($categoryName === 'accessBlock'): ?>
<?php include_component('member', 'accessBlockList') ?>
<?php endif; ?>
<?php else: ?>
<?php echo __('Please select the item from the menu.'); ?>
<?php endif; ?>
