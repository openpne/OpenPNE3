<?php ob_start() ?>
<?php $partsInfo = ob_get_contents() ?>
<?php ob_end_clean() ?>
<?php ob_start() ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo link_to(image_tag_sf_image($package->getImageFileName(), array('size' => '76x76')), '@package_home?name='.$package->name) ?> </td></tr>
<tr><th><?php echo __('Name') ?></th><td><?php echo link_to($package->name, '@package_home?name='.$package->name) ?></td></tr>
<?php $firstRow = ob_get_contents() ?>
<?php ob_end_clean() ?>
<?php op_include_form('JoinPluginTeam', $form, array(
  'title' => __('Join this plugin developer team'),
  'body' => __('Send request to join this plugin developer team'),
  'partsInfo' => $partsInfo,
  'firstRow' => $firstRow
)); ?>
