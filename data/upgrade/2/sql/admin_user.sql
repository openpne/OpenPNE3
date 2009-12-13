INSERT INTO admin_user (id, username, password, created_at, updated_at) (SELECT c_admin_user_id, username, password, NOW(), NOW() FROM c_admin_user WHERE auth_type = "all");
