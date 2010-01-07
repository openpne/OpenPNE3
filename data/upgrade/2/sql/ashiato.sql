INSERT INTO ashiato (id, member_id_from , member_id_to, r_date, created_at, updated_at) (SELECT c_ashiato_id, c_member_id_from, c_member_id_to, r_date, r_datetime, r_datetime FROM c_ashiato);

DROP TABLE c_ashiato;

