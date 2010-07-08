<?php ob_start() ?>
<?php $partsInfo = ob_get_contents() ?>
<?php ob_end_clean() ?>
<?php ob_start() ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo link_to(op_image_tag_sf_image($member->getImageFileName(), array('size' => '76x76')), '@member_profile?id='.$id) ?> </td></tr>
<tr><th><?php echo __('%nickname%', array('%nickname%' => $op_term['nickname']->titleize())) ?></th><td><?php echo link_to($member->getName(), '@member_profile?id='.$id) ?></td></tr>
<?php $firstRow = ob_get_contents() ?>
<?php ob_end_clean() ?>
<?php op_include_form('friendLink', $form, array(
  'title' => __('Add %my_friend%', array('%my_friend%' => $op_term['my_friend']->pluralize())),
  'partsInfo' => $partsInfo,
  'firstRow' => $firstRow
)); ?>
