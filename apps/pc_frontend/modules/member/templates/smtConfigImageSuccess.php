<div class="row">
  <div class="gadget_header span12"><?php echo __('Edit Photo') ?></div>
</div>

<?php $errors = array(); ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php $errors[] = $form->renderGlobalErrors(); ?>
<?php endif; ?>
<?php if ($errors): ?>
<div class="row">
<div class="alert-message block-message error">
<a class="close" href="#">x</a>
<?php foreach ($errors as $error): ?>
<p><?php echo __($error) ?></p>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<div class="row">
<?php echo $form->renderFormTag(url_for('member/configImage')) ?>
<?php echo $form['file'] ?>
<?php echo $form->renderHiddenFields(); ?>
<input type="submit" name="submit" value="<?php echo __('Upload') ?>" class="btn btn-primary" />
</form>
<?php if (3 >= $sf_user->getMember()->getMemberImage()->count()): ?>
<?php echo __('Send E-mail that has a photo to use as your image.') ?><br>
<?php echo op_mail_to('member_add_image', array(), __('Send E-mail')) ?>
<?php endif; ?>
</div>

<div class="row">
<?php $member = $sf_user->getMember() ?>
<?php $images = $member->getMemberImage() ?>
<?php $form = new sfForm() ?>
<?php $csrfToken = '&'.$form->getCSRFFieldName().'='.$form->getCSRFToken() ?>
<?php for ($i = 0; $i < 3; $i++) : ?>
<div class="span4">
  <div class="row center"> 
<?php if (isset($images[$i])) : ?>
<?php $image = $images[$i]; ?>
<?php echo op_image_tag_sf_image($image->getFile(), array('size' => '120x120', 'width' => '80', 'height' => '80')) ?></div>
  <div class="row center">
[
<?php echo link_to(__('Delete'), 'member/deleteImage?member_image_id='.$image->getId().$csrfToken) ?> |
<?php if ($image->getIsPrimary()) : ?>
<?php echo(__('Main Photo')) ?>
<?php else: ?>
<?php echo link_to(__('Main Photo'), 'member/changeMainImage?member_image_id='.$image->getId().$csrfToken) ?>
<?php endif; ?>
]
<?php else: ?>
<?php echo op_image_tag('no_image.gif', array('size' => '80x80', 'alt' => '')) ?>
<?php endif; ?>
  </div>
</div>
<?php endfor; ?>
</div>
