<?php op_mobile_page_title($community->getName(), __('Join to "%1%"', array('%1%' => $community->getName()))); ?>

<?php echo __('Do you really join to the following community?') ?><br>

<font color="#999966"><?php echo __('Community') ?>:</font><br>
<?php echo $community->getName() ?>
<br><br>
<?php op_include_form('communityJoining', $form, array(
  'button' => __('Submit'),
  'align'  => 'center'
)) ?>
