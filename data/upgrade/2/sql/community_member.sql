INSERT INTO community_member (id, community_id, member_id, is_receive_mail_pc, is_receive_mail_mobile, position, created_at, updated_at) (SELECT c_commu_member_id, c_commu_id, c_member_id, is_receive_mail_pc, is_receive_mail, "", r_datetime, r_datetime FROM c_commu_member);

UPDATE community_member,c_commu SET position = "admin" WHERE community_id = c_commu.c_commu_id AND member_id = c_commu.c_member_id_admin;
