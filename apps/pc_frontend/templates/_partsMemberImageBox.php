<div class="dparts memberImageBox">
<div class="parts">
<p class="photo"><?php echo image_tag_sf_image($option['image'], array('size' => '180x180')) ?></p>
</div>
<p class="text"><?php echo $option['name'] ?></p>
<?php if ($option['moreInfo']) : ?>
<ul>
<?php foreach ($option['moreInfo'] as $key => $value) : ?>
<li><?php echo $option['moreInfo']->getRaw($key) ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>

