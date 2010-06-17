INSERT IGNORE INTO file (id, name, type, original_filename, created_at, updated_at) (SELECT c_image_id, filename, type, filename, r_datetime, r_datetime FROM c_image);
INSERT IGNORE INTO file_bin (file_id, bin, created_at, updated_at) (SELECT c_image_id, bin, r_datetime, r_datetime FROM c_image);

DROP TABLE c_image;
