<?php use_helper('opActivity') ?>

<?php echo op_link_to_member($activity->getMember()) ?>
&nbsp;<?php echo op_auto_link_text_for_mobile(op_decoration(nl2br($activity->getBody()))) ?>
<font color="<?php echo $op_color['core_color_19'] ?>">[<?php echo op_format_activity_time(strtotime($activity->getCreatedAt())) ?>]</font>
<?php if ($activity->getPublicFlag() !== ActivityDataTable::PUBLIC_FLAG_SNS): ?>
<font color="<?php echo $op_color['core_color_19'] ?>">[<?php echo $activity->getPublicFlagCaption() ?>]</font>
<?php endif; ?>
<?php if (!isset($isOperation) || $isOperation): ?>
<div align="right">
<?php $replies = $activity->getReplies() ?>
<?php if (0 < count($replies)): ?>
<?php echo link_to('ｺﾒﾝﾄ'.count($replies).'件', '@comment_timeline?id='.$activity->getId()) ?>
<?php else: ?>
<?php echo link_to('ｺﾒﾝﾄする', '@comment_timeline?id='.$activity->getId()) ?>
<?php endif; ?>
&nbsp;&nbsp;
<?php if ($activity->getMemberId() === $sf_user->getMemberId()): ?>
<?php echo link_to(__('Delete'), '@delete_timeline?id='.$activity->getId()) ?>
<?php endif; ?>
</div>
<?php endif; ?>
