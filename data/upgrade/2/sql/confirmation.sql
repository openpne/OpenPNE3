INSERT INTO community_member_position (id, community_id, member_id, community_member_id, name, created_at, updated_at) (SELECT NULL, c_commu_id, c_member_id_to, (SELECT cm.id FROM community_member AS cm WHERE cm.community_id = c_commu_id AND cm.member_id = c_member_id_to LIMIT 1), "admin_confirm", r_datetime, r_datetime FROM c_commu_admin_confirm);
INSERT INTO community_member_position (id, community_id, member_id, community_member_id, name, created_at, updated_at) (SELECT NULL, c_commu_id, c_member_id_to, (SELECT cm.id FROM community_member AS cm WHERE cm.community_id = c_commu_id AND cm.member_id = c_member_id_to LIMIT 1), "sub_admin_confirm", r_datetime, r_datetime FROM c_commu_sub_admin_confirm);

INSERT INTO community_member (id, community_id, member_id, is_pre, is_receive_mail_pc, is_receive_mail_mobile, created_at, updated_at) (SELECT NULL, c_commu_id, c_member_id, 1, 0, 0, r_datetime, r_datetime FROM c_commu_member_confirm);

DROP TABLE c_commu_admin_confirm;
DROP TABLE c_commu_member_confirm;
