<?php op_mobile_page_title($community->getName(), __('Edit %community% Photo')) ?>
<center>
<?php if ($community->getImageFileName()): ?>
<?php echo image_tag_sf_image($community->getFile(), array('size' => '120x120', 'format' => 'jpg')) ?><br>
<?php echo sprintf('[%s | %s]',
  link_to(__('Expansion'), sf_image_path($community->getFile(), array('size' => '320x320', 'format' => 'jpg'))),
  link_to(__('Delete'), 'community/deleteImage?id='.$community->getId())
) ?>
<br><br>
<?php else: ?>
<?php echo __('This %community% doesn\'t have a photo.') ?>
<?php endif; ?>
</center>
<hr color="<?php echo $op_color["core_color_12"] ?>">
<?php if (!$community->getImageFileName()): ?>
<?php echo __('Send E-mail that has a photo to use as %community%\'s image.') ?><br>
<?php echo op_mail_to('community_add_image', array('id' => $community->id), __('Send E-mail')) ?>
<?php else: ?>
<?php echo __('This %community% already has a photo. If you want to register a new one, you must delete this photo.') ?><br>
<?php endif; ?>

