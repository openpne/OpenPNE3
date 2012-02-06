<?php if ($categoryName): ?>
<?php op_mobile_page_title(__('Settings'), __($categoryCaptions[$categoryName])) ?>
<?php else: ?>
<?php op_mobile_page_title(__('Settings')) ?>
<?php endif; ?>

<?php if ($categoryName && $form->count() > 1): // except CSRF token field ?>
<?php op_include_form('configForm', $form, array(
  'url'    => url_for('member/config?category='.$categoryName),
  'align'  => 'center',
  'button' => __('Save')
)) ?>
<?php elseif ($categoryName && 1 === $form->count()) : ?>
<?php echo __('There is no available settings.'); ?>
<?php else: ?>
<?php echo __('Please select the item from the menu.'); ?>
<?php endif; ?>
