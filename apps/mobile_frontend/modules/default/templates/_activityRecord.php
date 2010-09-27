<?php use_helper('opActivity') ?>

<?php echo op_link_to_member($activity->getMember()) ?>
&nbsp;<?php echo op_activity_body_filter($activity) ?>
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
