<?php if (count($option['list'])) : ?>
<div class="parts nineTable">
<div class="partsHeading"><h3><?php echo __($option['title']) ?></h3></div>
<table>
<?php $row = ceil(count($option['list']) / $option['row']) ?>
<?php for ($i = $j = 1; $row >= $i; $i++) : ?>
<?php if ($option['type'] === 'full' || $option['type'] === 'only_image') : ?>
<tr class="photo">
<?php for ($j = ($i * $option['col']) - $option['col']; ($i * $option['col']) > $j; $j++) : ?>
<td><?php if (!empty($option['list'][$j])) : ?>
<?php echo link_to(image_tag_sf_image($option['list'][$j]->getImageFileName(), array('size' => '76x76')), $option['link_to'].$option['list'][$j]->getId()) ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php if ($option['type'] === 'full' || $option['type'] === 'only_name') : ?>
<tr class="text">
<?php for ($j = ($i * $option['col']) - $option['col']; ($i * $option['col']) > $j; $j++) : ?>
<td><?php if (!empty($option['list'][$j])) : ?>
<?php echo link_to($option['list'][$j]->getName(), 'member/profile?id='.$option['list'][$j]->getId()) ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php endfor; ?>
</table>
<?php if ($option['moreInfo']) : ?>
<div class="block moreInfo">
<ul class="moreInfo">
<?php foreach ($option['moreInfo'] as $key => $value) : ?>
<li><?php echo link_to($key, $value) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
</div>
<?php endif; ?>
