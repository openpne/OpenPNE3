<?php
$list = array(
  'top_before' => array(
    'c_siteadmin' => 'top_banner_html_before',
    'c_banner' => array('TOP', 'is_hidden_before'),
  ),
  'top_after' => array(
    'c_siteadmin' => 'top_banner_html_after',
    'c_banner' => array('TOP', 'is_hidden_after'),
  ),
  'side_before' => array(
    'c_siteadmin' => 'side_banner_html_before',
    'c_banner' => array('SIDE', 'is_hidden_before'),
  ),
  'side_after' => array(
    'c_siteadmin' => 'side_banner_html_after',
    'c_banner' => array('SIDE', 'is_hidden_after'),
  ),
);
?>

ALTER TABLE c_banner CHANGE image_filename image_filename text CHARACTER SET utf8 COLLATE utf8_unicode_ci;
INSERT INTO banner_image (id, file_id, url, name, created_at, updated_at) (SELECT c_banner_id, <?php echo $this->getSQLForFileId('image_filename') ?>, a_href, nickname, NOW(), NOW() FROM c_banner);
<?php foreach ($list as $k => $v): ?>
UPDATE banner, c_siteadmin SET banner.html = c_siteadmin.body, banner.is_use_html = 1 WHERE c_siteadmin.target = "<?php echo $v['c_siteadmin'] ?>" AND banner.name = "<?php echo $k ?>";
INSERT INTO banner_use_image (id, banner_id, banner_image_id, created_at, updated_at) (SELECT NULL, (SELECT id FROM banner WHERE name = "<?php echo $k ?>" LIMIT 1), c_banner_id, NOW(), NOW() FROM c_banner WHERE type = "<?php echo $v['c_banner'][0] ?>" AND <?php echo $v['c_banner'][1] ?> = 0 AND (SELECT id FROM banner WHERE name = "<?php echo $k ?>" AND (html = "" OR html IS NULL) LIMIT 1) IS NOT NULL);
<?php endforeach; ?>
UPDATE banner SET is_use_html = 0 WHERE html = "";

DROP TABLE c_banner;

