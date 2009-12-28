INSERT INTO member_config (id, member_id, name, value, created_at, updated_at) (SELECT NULL, c_member_id, "blog_url", rss, NOW(), NOW() FROM c_member WHERE rss <> "");
INSERT INTO member_config (id, member_id, name, value, created_at, updated_at) (SELECT NULL, c_member_id, "age_public_flag", 1, NOW(), NOW() FROM c_member WHERE public_flag_birth_year = "public");
INSERT INTO member_config (id, member_id, name, value, created_at, updated_at) (SELECT NULL, c_member_id, "age_public_flag", 2, NOW(), NOW() FROM c_member WHERE public_flag_birth_year = "friend");
INSERT INTO member_config (id, member_id, name, value, created_at, updated_at) (SELECT NULL, c_member_id, "age_public_flag", 3, NOW(), NOW() FROM c_member WHERE public_flag_birth_year = "private");
INSERT INTO member_config (id, member_id, name, value_datetime, created_at, updated_at) (SELECT NULL, c_member_id, "lastLogin", access_date, NOW(), NOW() FROM c_member);
INSERT INTO member_config (id, member_id, name, value, created_at, updated_at) (SELECT NULL, c_member_id, "op_ashiato_count", ashiato_count_log, NOW(), NOW() FROM c_member);

