INSERT INTO favorite (id, member_id_to, member_id_from, created_at, updated_at) (SELECT c_bookmark_id, c_member_id_to, c_member_id_from, r_datetime, r_datetime FROM c_bookmark);
