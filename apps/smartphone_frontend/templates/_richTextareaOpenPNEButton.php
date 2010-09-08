<?php use_helper('Javascript') ?>
<?php foreach ($configs as $key => $config): ?>
<a id="<?php echo $id ?>_button_<?php echo $key ?>" href="#" onclick="<?php
  echo isset($onclick_actions[$key]) ? 
  preg_replace('/%id%/', $id, $onclick_actions[$key]) :
  "op_mce_insert_tagname('".$id."', '".str_replace("_", ":", $key)."');"
?> return false;">
<?php echo op_image_tag($config['imageURL'], array('alt' => '')) ?></a>
<?php endforeach; ?>
<?php echo javascript_tag('opEmoji.getInstance("'.$id.'").createEmojiPallet();'); ?>
