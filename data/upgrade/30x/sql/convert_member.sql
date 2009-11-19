ALTER TABLE member MODIFY is_active tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE member ADD is_login_rejected tinyint(1) NOT NULL DEFAULT '0';
