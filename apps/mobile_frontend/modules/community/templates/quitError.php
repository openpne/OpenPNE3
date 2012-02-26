<?php op_mobile_page_title(__('Error')) ?>
<?php if ($isAdmin): ?>
<font color="<?php echo $op_color['core_color_22'] ?>"><?php echo __('The administrator doesn\'t leave the %community%.') ?></font>
<?php else: ?>
<font color="<?php echo $op_color['core_color_22'] ?>"><?php echo __('You don\'t join this %community% yet.') ?></font>
<?php endif; ?>
