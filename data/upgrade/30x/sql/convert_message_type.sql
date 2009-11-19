ALTER TABLE message_type CHANGE foreignTable foreign_table text;
ALTER TABLE message_type MODIFY is_deleted tinyint(1) NOT NULL DEFAULT '0';
