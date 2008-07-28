
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
	`is_active` INTEGER,
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
	`name` TEXT,
	`is_required` INTEGER,
	`is_unique` INTEGER,
	`form_type` VARCHAR(32),
	`value_type` VARCHAR(32),
	`value_regexp` TEXT,
	`value_min` INTEGER,
	`value_max` INTEGER,
	`is_disp_regist` INTEGER,
	`is_disp_config` INTEGER,
	`is_disp_search` INTEGER,
	`sort_order` INTEGER,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- profile_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `profile_i18n`;


CREATE TABLE `profile_i18n`
(
	`caption` TEXT,
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

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
