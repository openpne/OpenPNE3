
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
	`created_at` DATETIME,
	`updated_at` DATETIME,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- authentication_pc_address
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `authentication_pc_address`;


CREATE TABLE `authentication_pc_address`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`member_id` INTEGER,
	`pc_address` VARCHAR(128),
	`password` VARCHAR(32),
	PRIMARY KEY (`id`),
	UNIQUE KEY `authentication_pc_address_U_1` (`pc_address`),
	INDEX `authentication_pc_address_FI_1` (`member_id`),
	CONSTRAINT `authentication_pc_address_FK_1`
		FOREIGN KEY (`member_id`)
		REFERENCES `member` (`id`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
