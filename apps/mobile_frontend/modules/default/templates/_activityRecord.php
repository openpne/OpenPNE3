<?php echo op_link_to_member($activity->getMember()) ?>
<?php if ($activity->getUri()): ?>
<?php echo link_to($activity->getBody(), $activity->getUri()) ?>
<?php else: ?>
<?php echo $activity->getBody() ?>
<?php endif; ?>
<font color="<?php echo $op_color['core_color_19'] ?>">[<?php echo op_format_activity_time(strtotime($activity->getCreatedAt())) ?>]</font>
<?php if ($activity->getPublicFlag() != ActivityDataTable::PUBLIC_FLAG_SNS): ?>
<font color="<?php echo $op_color['core_color_19'] ?>">[<?php echo $activity->getPublicFlagCaption() ?>]</font>
<?php endif; ?>
<?php if (!isset($isOperation) || $isOperation): ?>
<div align="right">
<?php if ($activity->getMemberId() == $sf_user->getMemberId()): ?>
<?php echo link_to(__('Delete'), 'member/deleteActivity?id='.$activity->getId()) ?>
<?php endif; ?>
</div>
<?php endif; ?>
