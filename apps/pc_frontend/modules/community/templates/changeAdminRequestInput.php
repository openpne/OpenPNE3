<?php slot('firstRow') ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo link_to(image_tag_sf_image($member->getImageFileName(), array('size' => '76x76')), 'member/profile?id='.$member->getId()) ?> </td></tr>
<tr><th><?php echo __('%nickname%', array('%nickname%' => $op_term['nickname']->titleize())) ?></th><td><?php echo link_to($member->getName(), 'member/profile?id='.$member->getId()) ?></td></tr>
<?php end_slot() ?>
<?php op_include_form('communityAdminRequest', $form, array(
  'title' => __('Take over the administrator of "%1%"', array('%1%' => $community->getName())),
  'firstRow' => get_slot('firstRow')
)) ?>
