<?php use_helper('opMessage') ?>
<?php echo op_format_date($message->getCreatedAt(), 'XDateTime') ?><br> 
<?php echo sprintf('%s (%s)',
  link_to(op_truncate($message->getSubject(), 28), '@readSendMessage?id='. $message->getId()),
  op_message_link_to_member($message->getSendTo())
);
