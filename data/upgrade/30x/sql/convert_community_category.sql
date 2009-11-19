ALTER TABLE community_category CHANGE lft_key lft int(11) DEFAULT NULL;
ALTER TABLE community_category CHANGE rht_key rgt int(11) DEFAULT NULL;
ALTER TABLE community_category MODIFY is_allow_member_community tinyint(1) NOT NULL DEFAULT '1';
ALTER TABLE community_category ADD level smallint(6) DEFAULT NULL;

UPDATE community_category SET level = 1;
UPDATE community_category SET level = 0 WHERE id = tree_key;

