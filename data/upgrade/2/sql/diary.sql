INSERT INTO diary (id, member_id, title, body, public_flag, has_images, created_at, updated_at) (SELECT c_diary_id, c_member_id, subject, body, public_flag, 1, r_datetime, u_datetime FROM c_diary WHERE image_filename_1 <> "" OR image_filename_2 <> "" OR image_filename_3 <> "");
INSERT INTO diary (id, member_id, title, body, public_flag, has_images, created_at, updated_at) (SELECT c_diary_id, c_member_id, subject, body, public_flag, 0, r_datetime, u_datetime FROM c_diary WHERE image_filename_1 = "" AND image_filename_2 = "" AND image_filename_3 = "");
<?php for ($i = 1; $i <= 3; $i++): ?>
ALTER TABLE c_diary CHANGE image_filename_<?php echo $i ?> image_filename_<?php echo $i ?> text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
INSERT INTO diary_image (diary_id, file_id, number) (SELECT c_diary_id, <?php echo $this->getSQLForFileId('image_filename_'.$i) ?>, <?php echo $i ?> FROM c_diary WHERE image_filename_<?php echo $i ?> <> "");
<?php endfor; ?>

INSERT INTO diary_comment_unread (diary_id, member_id) (SELECT c_diary_id, c_member_id FROM c_diary WHERE is_checked = 0);

INSERT INTO diary_comment (id, diary_id, member_id, number, body, created_at, updated_at) (SELECT c_diary_comment_id, c_diary_id, c_member_id, number, body, r_datetime, r_datetime FROM c_diary_comment);
<?php for ($i = 1; $i <= 3; $i++): ?>
ALTER TABLE c_diary_comment CHANGE image_filename_<?php echo $i ?> image_filename_<?php echo $i ?> text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
INSERT INTO diary_comment_image (diary_comment_id, file_id) (SELECT c_diary_comment_id, <?php echo $this->getSQLForFileId('image_filename_'.$i) ?> FROM c_diary_comment WHERE image_filename_<?php echo $i ?> <> "");
<?php endfor; ?>

INSERT INTO diary_comment_update (member_id, diary_id, last_comment_time, my_last_comment_time) (SELECT c_member_id, c_diary_id, MAX(r_datetime), MAX(r_datetime) FROM c_diary_comment_log WHERE c_diary_id IN (SELECT diary.id FROM diary WHERE diary.id = c_diary_id) GROUP BY c_member_id, c_diary_id);

DROP TABLE c_diary;
DROP TABLE c_diary_comment;
DROP TABLE c_diary_comment_log;

