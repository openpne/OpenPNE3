
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
	`name` VARCHAR(64)  NOT NULL,
	`is_active` INTEGER  NOT NULL,
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- profile
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `profile`;


CREATE TABLE `profile`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	`is_required` INTEGER  NOT NULL,
	`is_unique` INTEGER  NOT NULL,
	`form_type` VARCHAR(32)  NOT NULL,
	`value_type` VARCHAR(32)  NOT NULL,
	`value_regexp` TEXT,
	`value_min` INTEGER,
	`value_max` INTEGER,
	`is_disp_regist` INTEGER  NOT NULL,
	`is_disp_config` INTEGER  NOT NULL,
	`is_disp_search` INTEGER  NOT NULL,
	`sort_order` INTEGER,
	PRIMARY KEY (`id`),
	UNIQUE KEY `profile_U_1` (`name`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- profile_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `profile_i18n`;


CREATE TABLE `profile_i18n`
(
	`caption` TEXT  NOT NULL,
	`info` TEXT,
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `profile_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `profile` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- profile_option
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `profile_option`;


CREATE TABLE `profile_option`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`profile_id` INTEGER,
	`sort_order` INTEGER,
	PRIMARY KEY (`id`),
	INDEX `profile_option_FI_1` (`profile_id`),
	CONSTRAINT `profile_option_FK_1`
		FOREIGN KEY (`profile_id`)
		REFERENCES `profile` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- profile_option_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `profile_option_i18n`;


CREATE TABLE `profile_option_i18n`
(
	`value` TEXT,
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `profile_option_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `profile_option` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- member_profile
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `member_profile`;


CREATE TABLE `member_profile`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`member_id` INTEGER,
	`profile_id` INTEGER,
	`profile_option_id` INTEGER,
	`value` TEXT,
	PRIMARY KEY (`id`),
	INDEX `member_profile_FI_1` (`member_id`),
	CONSTRAINT `member_profile_FK_1`
		FOREIGN KEY (`member_id`)
		REFERENCES `member` (`id`),
	INDEX `member_profile_FI_2` (`profile_id`),
	CONSTRAINT `member_profile_FK_2`
		FOREIGN KEY (`profile_id`)
		REFERENCES `profile` (`id`),
	INDEX `member_profile_FI_3` (`profile_option_id`),
	CONSTRAINT `member_profile_FK_3`
		FOREIGN KEY (`profile_option_id`)
		REFERENCES `profile_option` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- friend
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `friend`;


CREATE TABLE `friend`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`member_id_to` INTEGER  NOT NULL,
	`member_id_from` INTEGER  NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `member_id_to_from` (`member_id_to`, `member_id_from`),
	UNIQUE KEY `member_id_from_to` (`member_id_from`, `member_id_to`),
	CONSTRAINT `friend_FK_1`
		FOREIGN KEY (`member_id_to`)
		REFERENCES `member` (`id`),
	CONSTRAINT `friend_FK_2`
		FOREIGN KEY (`member_id_from`)
		REFERENCES `member` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- community
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `community`;


CREATE TABLE `community`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `community_U_1` (`name`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- community_member
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `community_member`;


CREATE TABLE `community_member`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`community_id` INTEGER  NOT NULL,
	`member_id` INTEGER  NOT NULL,
	`position` VARCHAR(32),
	PRIMARY KEY (`id`),
	KEY `community_member_I_1`(`position`),
	INDEX `community_member_FI_1` (`community_id`),
	CONSTRAINT `community_member_FK_1`
		FOREIGN KEY (`community_id`)
		REFERENCES `community` (`id`),
	INDEX `community_member_FI_2` (`member_id`),
	CONSTRAINT `community_member_FK_2`
		FOREIGN KEY (`member_id`)
		REFERENCES `member` (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- admin_user
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `admin_user`;


CREATE TABLE `admin_user`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(64)  NOT NULL,
	`password` VARCHAR(40)  NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `admin_user_U_1` (`username`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- sns_config
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `sns_config`;


CREATE TABLE `sns_config`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(64)  NOT NULL,
	`value` TEXT,
	PRIMARY KEY (`id`),
	UNIQUE KEY `sns_config_U_1` (`name`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
