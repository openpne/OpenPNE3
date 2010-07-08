<?php slot('firstRow') ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo op_link_to_member($member, array('link_target' => op_image_tag_sf_image($member->getImageFileName(), array('size' => '76x76')))) ?> </td></tr>
<tr><th><?php echo __('%nickname%', array('%nickname%' => $op_term['nickname']->titleize())) ?></th><td><?php echo op_link_to_member($member) ?></td></tr>
<?php end_slot() ?>
<?php op_include_form('communitySubAdminRequest', $form, array(
  'title' => __('Request the sub-administrator of "%1%"', array('%1%' => $community->getName())),
  'firstRow' => get_slot('firstRow')
)) ?>
