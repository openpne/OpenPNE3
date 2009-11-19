INSERT INTO ashiato (updated_at) SELECT created_at FROM ashiato WHERE updated_at IS NULL;
ALTER TABLE ashiato MODIFY member_id_from int(11) DEFAULT NULL;
ALTER TABLE ashiato MODIFY member_id_to int(11) DEFAULT NULL;
