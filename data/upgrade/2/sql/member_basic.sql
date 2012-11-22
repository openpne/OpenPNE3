INSERT INTO member (id, name, is_active, is_login_rejected, created_at, updated_at) (SELECT c_member_id, nickname, 1, is_login_rejected, r_date, u_datetime FROM c_member);
UPDATE member, c_member SET invite_member_id = c_member_id_invite WHERE id = c_member_id AND c_member_id_invite <> 0;

