<div class="row">
  <div class="gadget_header span12"><?php echo __('Profile Photo') ?></div>
</div>
<div class="row">
  <div class="span12 center">
    <p class="photo" style="margin-top: 8px;">
      <?php $imgParam = array('size' => '180x180', 'alt' => $member->getName()) ?>
      <?php if ($member): ?>
        <?php echo op_image_tag_sf_image($member->getImageFileName(), $imgParam) ?>
      <?php else: ?>
        <?php echo op_image_tag('no_image.gif', $imgParam) ?>
      <?php endif; ?>
    </p>
    <p class="text"><?php echo $member->getNameAndCount() ?></p>
  </div>
</div>
