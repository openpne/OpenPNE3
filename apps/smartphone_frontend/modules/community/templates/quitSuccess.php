<?php slot('firstRow') ?>
<tr><th><?php echo __('Photo') ?></th><td><?php echo link_to(op_image_tag_sf_image($community->getImageFileName(), array('size' => '76x76')), '@community_home?id='.$id) ?> </td></tr>
<tr><th><?php echo __('%community%', array('%community%' => $op_term['community']->titleize())) ?></th><td><?php echo link_to($community->getName(), '@community_home?id='.$id) ?></td></tr>
<?php end_slot() ?>
<?php op_include_form('communityQuiting', $form, array(
  'title'    => __('Quit "%1%"', array('%1%' => $community->getName())),
  'body'     => __('Do you really quit the following %community%?'),
  'firstRow' => get_slot('firstRow')
)) ?>
