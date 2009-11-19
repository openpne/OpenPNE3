ALTER TABLE member_profile CHANGE lft_key lft int(11) DEFAULT NULL;
ALTER TABLE member_profile CHANGE rht_key rgt int(11) DEFAULT NULL;
ALTER TABLE member_profile MODIFY tree_key bigint(20) DEFAULT NULL;
ALTER TABLE member_profile ADD level smallint(6) DEFAULT NULL;
ALTER TABLE member_profile ADD value_datetime datetime DEFAULT NULL;

UPDATE member_profile SET level = 1;
UPDATE member_profile SET level = 0 WHERE value IS NULL;

