INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "description", info, r_datetime, u_datetime FROM c_commu);

<?php if ('2.12' == sfConfig::get('op_upgrade2_version')): ?>
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "topic_authority", topic_authority, r_datetime, u_datetime FROM c_commu);

INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "register_poricy", "open", r_datetime, u_datetime FROM c_commu WHERE public_flag = "public");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "public_flag", "public", r_datetime, u_datetime FROM c_commu WHERE public_flag = "public");

INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "register_poricy", "close", r_datetime, u_datetime FROM c_commu WHERE public_flag = "auth_public");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "public_flag", "public", r_datetime, u_datetime FROM c_commu WHERE public_flag = "auth_public");

INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "register_poricy", "close", r_datetime, u_datetime FROM c_commu WHERE public_flag = "auth_sns");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "public_flag", "public", r_datetime, u_datetime FROM c_commu WHERE public_flag = "auth_sns");

INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "register_poricy", "close", r_datetime, u_datetime FROM c_commu WHERE public_flag = "auth_commu_member");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "public_flag", "auth_commu_member", r_datetime, u_datetime FROM c_commu WHERE public_flag = "auth_commu_member");
<?php else: ?>
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "register_poricy", "open", r_datetime, u_datetime FROM c_commu WHERE is_admit = "public");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "register_poricy", "close", r_datetime, u_datetime FROM c_commu WHERE is_admit = "auth");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "public_flag", "public", r_datetime, u_datetime FROM c_commu WHERE is_open = "public");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "public_flag", "auth_commu_member", r_datetime, u_datetime FROM c_commu WHERE is_open = "member");

INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "topic_authority", "public", r_datetime, u_datetime FROM c_commu WHERE is_topic = "member");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "topic_authority", "public", r_datetime, u_datetime FROM c_commu WHERE is_topic = "public");
INSERT INTO community_config (community_id, name, value, created_at, updated_at) (SELECT c_commu_id, "topic_authority", "admin_only", r_datetime, u_datetime FROM c_commu WHERE is_topic = "admin_only");
<?php endif; ?>
