<?php op_mobile_page_title(__('Settings'), __('Delete access block') ) ?>
<?php echo __('Are you sure to delete %name% (Member ID:%id%) from access block list?', array('%name%'=> $blockMember->getName(), '%id%' => $blockMember->getId())); ?>

<?php op_include_form('deleteForm', $form, array(
  'url'    => url_for('@member_accessBlock_delete?id='.$id),
  'button' => __('Delete'),
  'align'  => 'center'
)) ?>
