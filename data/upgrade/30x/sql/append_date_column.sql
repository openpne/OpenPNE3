<?php
$target = array(
  'admin_user' => 'both',
  'banner_image' => 'both',
  'banner_use_image' => 'both',
  'blacklist' => 'both',
  'community_config' => 'both',
  'community_category' => 'both',
  'community_event_comment' => 'updated_at',
  'community_event_member' => 'both',
  'community_topic_comment' => 'updated_at',
  'community_member' => 'both',
  'diary_comment' => 'updated_at',
  'file_bin' => 'both',
  'gadget' => 'both',
  'gadget_config' => 'both',
  'member_config' => 'both',
  'member_image' => 'both',
  'member_relationship' => 'both',
  'member_profile' => 'both',
  'message_file' => 'both',
  'message_type' => 'both',
  'navigation' => 'both',
  'profile' => 'both',
  'profile_option' => 'both',
);
?>

<?php foreach ($target as $k => $v): ?>
<?php if ('both' === $v || 'created_at' === $v): ?>
ALTER TABLE <?php echo $k ?> ADD created_at datetime;
UPDATE <?php echo $k ?> SET created_at = NOW();
ALTER TABLE <?php echo $k ?> MODIFY created_at datetime NOT NULL;
<?php endif; ?>
<?php if ('both' === $v || 'updated_at' === $v): ?>
ALTER TABLE <?php echo $k ?> ADD updated_at datetime;
UPDATE <?php echo $k ?> SET updated_at = created_at;
ALTER TABLE <?php echo $k ?> MODIFY updated_at datetime NOT NULL;
<?php endif; ?>
<?php endforeach; ?>

ALTER TABLE ashiato ADD created_at datetime;
UPDATE ashiato SET created_at = r_date;
ALTER TABLE ashiato MODIFY created_at datetime NOT NULL;
