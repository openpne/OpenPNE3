<?php
$nums = array(
  1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15,
  17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28,
);
?>

<?php foreach ($nums as $num): ?>
INSERT INTO sns_config (id, name, value) (SELECT NULL, "mobile_frontend_core_color_<?php echo $num ?>", CONCAT("#", color_<?php echo $num ?>) FROM c_config_color_ktai LIMIT 1);
<?php endforeach; ?>

DROP TABLE c_config_color_ktai;
