ALTER TABLE c_album CHANGE album_cover_image album_cover_image text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE c_album_image CHANGE image_filename image_filename text CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO album (id, member_id, title, body, public_flag, created_at, updated_at, file_id) (SELECT c_album_id, c_member_id, subject, description, public_flag, r_datetime, u_datetime, <?php echo $this->getSQLForFileId('album_cover_image') ?> FROM c_album);

INSERT INTO album_image (id, album_id, member_id, file_id, description, filesize, created_at, updated_at) (SELECT c_album_image_id, c_album_id, c_member_id, <?php echo $this->getSQLForFileId('image_filename') ?>, image_description, filesize, r_datetime, r_datetime FROM c_album_image);

DROP TABLE c_album;

DROP TABLE c_album_image;

