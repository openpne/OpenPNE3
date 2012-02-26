<?php use_helper('sfImage')?>
<div class="cell">
<dl>
<dt class="day"><?php echo $image->getCreatedAt() ?></dt>
<dd class="upImage">
<a href="<?php echo sf_image_path($image->getName(), array('format' => $image->getImageFormat())) ?>">
<?php echo image_tag_sf_image($image->getName(), array('size' => '120x120', 'format' => $image->getImageFormat())) ?>
</a>
</dd>
<dd class="fileName"><?php echo $image->getName() ?></dd>
<?php if ($deleteBtn): ?>
<dd class="delete"> 
[ <?php echo link_to(__('削除する'), 'monitoring/deleteImage?id='.$image->getId()) ?> ]
</dd>
<?php endif; ?>
</dl>
</div>
