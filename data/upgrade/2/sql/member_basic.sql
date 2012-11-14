INSERT INTO member (id, name, is_active, is_login_rejected, created_at, updated_at) (SELECT c_member_id, nickname, 1, is_login_rejected, r_date, u_datetime FROM c_member);
INSERT INTO c_member value("","","","","","","","","","","","","","","","","","","","","","","","","","");
SET @lastId = (SELECT last_insert_id());
SET @auto_inc_query = CONCAT('ALTER TABLE member AUTO_INCREMENT = ', @lastId);
PREPARE query_prepare FROM @auto_inc_query;
EXECUTE query_prepare;
UPDATE member, c_member SET invite_member_id = c_member_id_invite WHERE id = c_member_id AND c_member_id_invite <> 0;

