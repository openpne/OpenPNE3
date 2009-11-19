ALTER TABLE community_member MODIFY position varchar(32) DEFAULT NULL;
ALTER TABLE community_member ADD is_receive_mail_pc tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE community_member ADD is_receive_mail_mobile tinyint(1) NOT NULL DEFAULT '0';
