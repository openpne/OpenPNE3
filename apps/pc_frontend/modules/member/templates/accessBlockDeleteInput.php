<div id="formDiaryDelete" class="dparts box"><div class="parts">
<div class="partsHeading">
<h3><?php echo __('Delete access block') ?></h3>
</div>
<div class="block">
<p><?php echo __('Are you sure to delete %name% (Member ID:%id%) from access block list?', array('%name%'=> $blockMember->getName(), '%id%' => $blockMember->getId())); ?></p>
<?php op_include_form('deleteForm', $form, array(
  'url'    => url_for('@member_accessBlock_delete?id='.$id),
  'button' => __('Delete'),
  'align'  => 'center'
)) ?>
</div>
</div></div>



