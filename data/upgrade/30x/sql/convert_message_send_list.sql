ALTER TABLE message_send_list MODIFY is_read tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE message_send_list MODIFY is_deleted tinyint(1) NOT NULL DEFAULT '0';
