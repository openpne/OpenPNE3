<?php
$targets = array(
  'application', 'banner', 'message_type', 'navigation', 'profile', 'profile_option',
);
?>

<?php foreach ($targets as $target): ?>
ALTER TABLE <?php echo $target ?>_i18n CHANGE culture lang varchar(7) NOT NULL;
INSERT INTO <?php echo $target ?>_translation SELECT 
<?php endforeach; ?>

