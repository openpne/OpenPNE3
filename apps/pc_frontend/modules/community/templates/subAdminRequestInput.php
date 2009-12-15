<?php slot('firstRow') ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo link_to(image_tag_sf_image($member->getImageFileName(), array('size' => '76x76')), 'member/profile?id='.$id) ?> </td></tr>
<tr><th><?php echo __('%nickname%', array('%nickname%' => $op_term['nickname']->titleize())) ?></th><td><?php echo link_to($member->getName(), 'member/profile?id='.$id) ?></td></tr>
<?php end_slot() ?>
<?php op_include_form('communitySubAdminRequest', $form, array(
  'title' => __('Request the sub-administrator of "%1%"', array('%1%' => $community->getName())),
  'firstRow' => get_slot('firstRow')
)) ?>
