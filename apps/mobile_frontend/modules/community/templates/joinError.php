<?php op_mobile_page_title(__('Error')) ?>
<?php if ($isCommunityMember): ?>
<font color="<?php echo $op_color['core_color_22'] ?>"><?php echo __('You are already joined to this %community%.') ?></font>
<?php else: ?>
<font color="<?php echo $op_color['core_color_22'] ?>"><?php echo __('You have already sent the participation request to this %community%.') ?></font>
<?php endif; ?>
