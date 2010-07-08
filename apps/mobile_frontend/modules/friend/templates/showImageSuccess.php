<?php op_mobile_page_title($member->getName(), __('Photo')) ?>
<center>
<?php $images = $member->getMemberImage() ?>
<?php for ($i = 0; $i < 3 && $i < count($images); $i++) : ?>
<?php $image = $images[$i] ?>
<?php echo op_image_tag_sf_image($image->getFile(), array('size' => '120x120', 'format' => 'jpg')) ?><br>
<?php echo '['.link_to(__('Expansion'), sf_image_path($image->getFile(), array('size' => '320x320', 'format' => 'jpg'))).']' ?><br><br>
<?php endfor; ?>
</center>
<?php slot('op_mobile_footer_menu') ?>
<?php echo op_link_to_member($member, array('link_target' => __("%1%'s Profile", array('%1%' => $member->getName())))); ?>
<?php end_slot(); ?>
