INSERT INTO blacklist (id, uid, memo, created_at, updated_at) (SELECT c_blacklist_id, easy_access_id, info, NOW(), NOW() FROM c_blacklist);

DROP TABLE c_blacklist;
