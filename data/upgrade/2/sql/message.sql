INSERT INTO message (id, member_id, subject, body, is_deleted, is_send, thread_message_id, return_message_id, message_type_id, foreign_id, created_at, updated_at) (SELECT c_message_id, c_member_id_from, subject, body, is_deleted_from, is_send, NULL, hensinmoto_c_message_id, 1, NULL, r_datetime, r_datetime FROM c_message);
UPDATE message SET is_deleted = 1 WHERE id IN (SELECT c_message_id FROM c_message WHERE is_kanzen_sakujo_from = 1);

INSERT INTO message_send_list (id, member_id, message_id, is_read, is_deleted, created_at, updated_at) (SELECT c_message_id, c_member_id_to, c_message_id, is_read, is_deleted_to, r_datetime, r_datetime FROM c_message);
UPDATE message_send_list SET is_deleted = 1 WHERE id IN (SELECT c_message_id FROM c_message WHERE is_kanzen_sakujo_to = 1);

INSERT INTO deleted_message (id, member_id, message_id, message_send_list_id, is_deleted, created_at, updated_at) (SELECT NULL, c_member_id_from, c_message_id, NULL, is_kanzen_sakujo_from, NOW(), NOW() FROM c_message WHERE is_deleted_from = 1 OR is_kanzen_sakujo_from = 1);
INSERT INTO deleted_message (id, member_id, message_id, message_send_list_id, is_deleted, created_at, updated_at) (SELECT NULL, c_member_id_to, NULL, c_message_id, is_kanzen_sakujo_to, NOW(), NOW() FROM c_message WHERE is_deleted_to = 1 OR is_kanzen_sakujo_to = 1);

<?php foreach (array('image_filename_1', 'image_filename_2', 'image_filename_3') as $v): ?>
ALTER TABLE c_message CHANGE <?php echo $v ?> <?php echo $v ?> text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
INSERT INTO message_file (id, message_id, file_id, created_at, updated_at) (SELECT NULL, c_message_id, <?php echo $this->getSQLForFileId($v) ?>, NOW(), NOW() FROM c_message WHERE <?php echo $v ?> <> '');
<?php endforeach; ?>

DROP TABLE c_message;

