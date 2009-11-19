ALTER TABLE message MODIFY is_deleted tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE message MODIFY is_send tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE message ADD foreign_id int(11) DEFAULT '0';
