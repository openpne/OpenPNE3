INSERT INTO community_topic (id, community_id, member_id, name, body, topic_updated_at, created_at, updated_at) (SELECT c_commu_topic_id, c_commu_id, c_member_id, name, "", u_datetime, r_datetime, r_datetime FROM c_commu_topic WHERE event_flag = 0);
UPDATE community_topic,c_commu_topic_comment SET community_topic.body = c_commu_topic_comment.body WHERE community_topic.id = c_commu_topic_comment.c_commu_topic_id AND c_commu_topic_comment.number = 0;
INSERT INTO community_topic_comment (id, community_topic_id, member_id, number, body, created_at, updated_at) (SELECT c_commu_topic_comment_id, c_commu_topic_id, c_member_id, number, body, r_datetime, r_datetime FROM c_commu_topic_comment WHERE c_commu_topic_comment.number <> 0 AND c_commu_topic_id IN (SELECT c_commu_topic_id FROM c_commu_topic WHERE event_flag = 0));

<?php
$area = $this->conn->expression->concat('(SELECT c_profile_pref.pref FROM c_profile_pref WHERE c_profile_pref.c_profile_pref_id = open_pref_id)', '" "' , 'open_pref_comment');
?>

INSERT INTO community_event (id, community_id, member_id, name, body, event_updated_at, created_at, updated_at, open_date, open_date_comment, area, application_deadline, capacity) (SELECT c_commu_topic_id, c_commu_id, c_member_id, name, "", u_datetime, r_datetime, r_datetime, open_date, open_date_comment, <?php echo $area ?>, invite_period, capacity FROM c_commu_topic WHERE event_flag = 1);
UPDATE community_event,c_commu_topic_comment SET community_event.body = c_commu_topic_comment.body WHERE community_event.id = c_commu_topic_comment.c_commu_topic_id AND c_commu_topic_comment.number = 0;
INSERT INTO community_event_comment (id, community_event_id, member_id, number, body, created_at, updated_at) (SELECT c_commu_topic_comment_id, c_commu_topic_id, c_member_id, number, body, r_datetime, r_datetime FROM c_commu_topic_comment WHERE c_commu_topic_comment.number <> 0 AND c_commu_topic_id IN (SELECT c_commu_topic_id FROM c_commu_topic WHERE event_flag = 1));

INSERT INTO community_event_member (id, community_event_id, member_id, created_at, updated_at) (SELECT c_event_member_id, c_commu_topic_id, c_member_id, r_datetime, r_datetime FROM c_event_member);

<?php for ($i = 1; $i <= 3; $i++): ?>
ALTER TABLE c_commu_topic_comment CHANGE image_filename<?php echo $i ?> image_filename<?php echo $i ?> text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
INSERT INTO community_topic_image (post_id, file_id, number) (SELECT c_commu_topic_id, <?php echo $this->getSQLForFileId('image_filename'.$i) ?>, <?php echo $i ?> FROM c_commu_topic_comment WHERE image_filename<?php echo $i ?> <> "" AND number = 0 AND c_commu_topic_id IN (SELECT c_commu_topic_id FROM c_commu_topic WHERE event_flag = 0));
INSERT INTO community_topic_comment_image (post_id, file_id, number) (SELECT c_commu_topic_comment_id, <?php echo $this->getSQLForFileId('image_filename'.$i) ?>, <?php echo $i ?> FROM c_commu_topic_comment WHERE image_filename<?php echo $i ?> <> "" AND number <> 0 AND c_commu_topic_id IN (SELECT c_commu_topic_id FROM c_commu_topic WHERE event_flag = 0));
INSERT INTO community_event_image (post_id, file_id, number) (SELECT c_commu_topic_id, <?php echo $this->getSQLForFileId('image_filename'.$i) ?>, <?php echo $i ?> FROM c_commu_topic_comment WHERE image_filename<?php echo $i ?> <> "" AND number = 0 AND c_commu_topic_id IN (SELECT c_commu_topic_id FROM c_commu_topic WHERE event_flag = 1));
INSERT INTO community_event_comment_image (post_id, file_id, number) (SELECT c_commu_topic_comment_id, <?php echo $this->getSQLForFileId('image_filename'.$i) ?>, <?php echo $i ?> FROM c_commu_topic_comment WHERE image_filename<?php echo $i ?> <> "" AND number <> 0 AND c_commu_topic_id IN (SELECT c_commu_topic_id FROM c_commu_topic WHERE event_flag = 1));
<?php endfor; ?>

DROP TABLE c_commu_topic;
DROP TABLE c_commu_topic_comment;
DROP TABLE c_profile_pref;
