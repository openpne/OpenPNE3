<?php $options->setDefault('single', true) ?>

<p class="photo">
<?php $imgParam = array('size' => '180x180', 'alt' => $options['name']) ?>
<?php if ($options['image']): ?>
<?php echo image_tag_sf_image($options['image'], $imgParam) ?>
<?php else: ?>
<?php echo image_tag('no_image.gif', $imgParam) ?>
<?php endif; ?>
</p>
<p class="text"><?php echo $options['name'] ?></p>
