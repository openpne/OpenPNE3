<div class="dparts memberImageBox">
<div class="parts">
<p class="photo">
<?php $imgParam = array('size' => '180x180', 'alt' => $option['name']) ?>
<?php if ($option['image']) : ?>
<?php echo image_tag_sf_image($option['image'], $imgParam) ?>
<?php else : ?>
<?php echo image_tag('no_image.gif', $imgParam) ?>
<?php endif; ?>
</p>
</div>
<p class="text"><?php echo $option['name'] ?></p>
<?php if (!empty($option['moreInfo'])) : ?>
<ul>
<?php foreach ($option['moreInfo'] as $key => $value) : ?>
<li><?php echo $option['moreInfo']->getRaw($key) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>

