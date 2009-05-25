<?php op_mobile_page_title($member->getName(), __('Photo')) ?>
<center>
<?php $images = $member->getMemberImages() ?>
<?php for ($i = 0; $i < 3 && $i < count($images); $i++) : ?>
<?php $image = $images[$i] ?>
<?php echo image_tag_sf_image($image->getFile(), array('size' => '120x120', 'format' => 'jpg')) ?><br>
<?php echo '['.link_to(__('Expansion'), sf_image_path($image->getFile(), array('size' => '320x320', 'format' => 'jpg'))).']' ?><br><br>
<?php endfor; ?>
</center>
<hr color="#cccccc">
<?php slot('op_mobile_footer_menu') ?>
<?php echo link_to(__("%1%'s Profile", array('%1%' => $member->getName())), 'member/profile?id='.$member->getId()); ?>
<?php end_slot(); ?>
