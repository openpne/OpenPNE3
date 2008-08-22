
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- authentication_login_id
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `authentication_login_id`;


CREATE TABLE `authentication_login_id`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`member_id` INTEGER,
	`login_id` VARCHAR(128),
	`password` VARCHAR(32),
	PRIMARY KEY (`id`),
	UNIQUE KEY `authentication_login_id_U_1` (`login_id`),
	INDEX `authentication_login_id_FI_1` (`member_id`),
	CONSTRAINT `authentication_login_id_FK_1`
		FOREIGN KEY (`member_id`)
		REFERENCES `member` (`id`)
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
