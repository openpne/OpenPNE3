
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- member
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `member`;


CREATE TABLE `member`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`nickname` TEXT  NOT NULL,
	`created_at` DATETIME default '1970-01-01 00:00:00' NOT NULL,
	`updated_at` DATETIME default '1970-01-01 00:00:00' NOT NULL,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- member_secure
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `member_secure`;


CREATE TABLE `member_secure`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`member_id` INTEGER default 0 NOT NULL,
	`pc_address` MEDIUMBLOB  NOT NULL,
	`mobile_address` MEDIUMBLOB  NOT NULL,
	`regist_address` MEDIUMBLOB  NOT NULL,
	`password` MEDIUMBLOB  NOT NULL,
	`password_query_answer` MEDIUMBLOB  NOT NULL,
	`easy_access_id` MEDIUMBLOB  NOT NULL,
	`created_at` DATETIME default '1970-01-01 00:00:00' NOT NULL,
	`updated_at` DATETIME default '1970-01-01 00:00:00' NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `member_secure_U_1` (`member_id`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
