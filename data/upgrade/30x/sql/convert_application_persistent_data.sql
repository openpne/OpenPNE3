ALTER TABLE application_persistent_data MODIFY id bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE application_persistent_data MODIFY application_id bigint(20) NOT NULL;
ALTER TABLE application_persistent_data MODIFY member_id int(11) NOT NULL;
ALTER TABLE application_persistent_data MODIFY value text NULL;
