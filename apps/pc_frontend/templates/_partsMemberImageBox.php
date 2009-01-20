<p class="photo">
<?php $imgParam = array('size' => '180x180', 'alt' => $options['name']) ?>
<?php if ($options['image']): ?>
<?php echo image_tag_sf_image($options['image'], $imgParam) ?>
<?php else: ?>
<?php echo image_tag('no_image.gif', $imgParam) ?>
<?php endif; ?>
</p>
<p class="text"><?php echo $options['name'] ?></p>
<?php if (!empty($options['moreInfo'])): ?>
<ul>
<?php foreach ($options['moreInfo'] as $key => $value): ?>
<li><?php echo $options['moreInfo']->getRaw($key) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
