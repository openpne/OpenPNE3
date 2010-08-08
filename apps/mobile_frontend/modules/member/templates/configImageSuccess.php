<?php op_mobile_page_title(__('Settings'), __('Edit Photo')) ?>
<center>
<?php $_member = $sf_user->getMember() ?>
<?php $images = $_member->getMemberImage() ?>
<?php if ($images->count()): ?>
<?php $form = new sfForm() ?>
<?php $csrfToken = '&'.$form->getCSRFFieldName().'='.$form->getCSRFToken() ?>
<?php foreach ($images as $image) : ?>
<?php echo image_tag_sf_image($image->getFile(), array('size' => '120x120', 'format' => 'jpg')) ?><br>
<?php echo sprintf('[%s]',link_to(__('Expansion'), sf_image_path($image->getFile(), array('size' => '320x320', 'format' => 'jpg')))) ?><br>
<?php 
if ($image->getIsPrimary())
{
  $main = __('Main Photo');
}
else
{
  $main = link_to(__('Main Photo'), 'member/changeMainImage?member_image_id='.$image->getId().$csrfToken);
}
?>
<?php echo sprintf('[%s|%s]', link_to(__('Delete'), 'member/deleteImage?member_image_id='.$image->getId()), $main) ?>
<br><br>
<?php endforeach; ?>
<?php else: ?>
<?php echo __('There are no photos.') ?>
<?php endif; ?>
</center>

<?php if (3 >= $images->count()): ?>
<hr color="<?php echo $op_color["core_color_12"] ?>">
<?php echo __('Send E-mail that has a photo to use as your image.') ?><br>
<?php echo op_mail_to('member_add_image', array(), __('Send E-mail')) ?>
<?php endif; ?>
