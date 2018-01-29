<?php use_helper('opMessage') ?>
<?php echo op_format_date($message->getCreatedAt(), 'XDateTime') ?> 
<?php if ($message->getIsHensin()): ?>
<font color="<?php echo $op_color["core_color_15"] ?>">(<?php echo __('Replied') ?>)</font>
<?php elseif ($message->getIsRead()): ?>
(<?php echo __('Open') ?>)
<?php else: ?>
<font color="<?php echo $op_color["core_color_22"] ?>">(<?php echo __('Unopened') ?>)</font>
<?php endif; ?><br>
<?php echo sprintf('%s (%s)',
  link_to(op_truncate($message->getSubject(), 28), '@readReceiveMessage?id='. $message->getMessageId()),
  op_message_link_to_member($message->getSendFrom())
); ?>
