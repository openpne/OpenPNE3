<?php for ($i = 1; $i <= 3; $i++): ?>
ALTER TABLE c_member CHANGE image_filename_<?php echo $i ?> image_filename_<?php echo $i ?> text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
INSERT INTO member_image (id, member_id, file_id, is_primary, created_at, updated_at) (SELECT NULL, c_member_id, <?php echo $this->getSQLForFileId('image_filename_'.$i) ?>, 0, NOW(), NOW() FROM c_member WHERE image_filename_<?php echo $i ?> <> "");
<?php endfor; ?>

ALTER TABLE c_member CHANGE image_filename image_filename text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
UPDATE member_image SET is_primary = 1 WHERE file_id = (SELECT <?php echo $this->getSQLForFileId('image_filename') ?> FROM c_member WHERE c_member_id = member_id);

