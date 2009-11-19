ALTER TABLE profile MODIFY is_required tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE profile MODIFY is_unique tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE profile MODIFY is_edit_public_flag tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE profile MODIFY is_disp_regist tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE profile MODIFY is_disp_config tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE profile MODIFY is_disp_search tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE profile MODIFY is_disp_search tinyint(1) NOT NULL DEFAULT '0';
