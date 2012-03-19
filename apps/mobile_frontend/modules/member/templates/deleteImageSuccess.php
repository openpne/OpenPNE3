<?php op_mobile_page_title(__('Settings'), __('Delete Photo')) ?>
<?php echo __('Do you delete this photo?') ?>
<hr color="<?php echo $op_color["core_color_12"] ?>">
<center>
<?php echo op_image_tag_sf_image($image->getFile(), array('size' => '120x120', 'format' => 'jpg')) ?><br>
<?php echo sprintf('[%s]',link_to(__('Expansion'), sf_image_path($image->getFile(), array('size' => '320x320', 'format' => 'jpg')))) ?><br>
</center>
<hr color="<?php echo $op_color["core_color_12"] ?>">
<?php op_include_form('deleteForm', $form, array(
  'url'    => url_for('member/deleteImage?member_image_id='.$image->getId()),
  'button' => __('Delete'),
  'align'  => 'center'
)) ?>
