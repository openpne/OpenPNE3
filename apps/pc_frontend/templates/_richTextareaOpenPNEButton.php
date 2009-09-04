<?php use_helper('Javascript') ?>
<?php foreach ($configs as $key => $config): ?>
<a id="<?php echo $id ?>_button_<?php echo $key ?>" href="#" onclick="<?php echo isset($onclick_actions[$key]) ? $onclick_actions[$key] : "op_mce_insert_tagname('".$id."', '".str_replace("_", ":", $key)."');" ?> return false;">
<?php echo image_tag('deco_'.$key.'.gif', array('alt' => '')) ?></a>
<?php endforeach; ?>
<?php $instanceName = $id.'_image_palet'; ?>
<?php javascript_tag() ?>
var <?php echo $instanceName ?> = new opEmoji('<?php echo $id ?>');
<?php echo $instanceName?>.createEmojiPallet();
$('<?php echo $id ?>_button_op_emoji_docomo').onclick = function(){<?php echo $id ?>_image_palet.togglePallet('epDocomo');};
if (<?php echo $instanceName ?>.useAu) {
  $('<?php echo $id ?>_button_op_emoji_au').onclick = function(){<?php echo $id ?>_image_palet.togglePallet('epAu');};
}
if (<?php echo $instanceName ?>.useSb) {
  $('<?php echo $id ?>_button_op_emoji_softbank').onclick = function(){<?php echo $id ?>_image_palet.togglePallet('epSb');};
}
<?php end_javascript_tag() ?>
