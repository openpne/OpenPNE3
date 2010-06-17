INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) VALUES(NULL, "value", (SELECT id FROM gadget WHERE name = "informationBox" AND type = "top"), (SELECT body FROM c_siteadmin WHERE target = "h_home"), NOW(), NOW());
INSERT INTO gadget_config (id, name, gadget_id, value, created_at, updated_at) VALUES(NULL, "value", (SELECT id FROM gadget WHERE name = "informationBox" AND type = "mobileTop"), (SELECT body FROM c_siteadmin WHERE target = "k_h_home"), NOW(), NOW());

