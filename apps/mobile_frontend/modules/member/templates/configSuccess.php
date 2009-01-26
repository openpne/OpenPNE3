<?php if ($categoryName): ?>
<?php op_mobile_page_title(__('Settings'), __($categoryCaptions[$categoryName])) ?>
<?php else: ?>
<?php op_mobile_page_title(__('Settings')) ?>
<?php endif; ?>

<?php if ($categoryName): ?>
<form action="<?php echo url_for('member/config?category='.$categoryName) ?>" method="post">
<?php echo $form ?>
<br>
<center><input type="submit" value="<?php echo __('Save') ?>"></center>
</form>
<?php else: ?>
<?php echo __('Please select the item from the menu.'); ?>
<?php endif; ?>
