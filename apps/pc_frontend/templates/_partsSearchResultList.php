<?php $raw_options = $sf_data->getRaw('options'); ?>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($options['pager'], $options['link_to_page']); ?></p></div>

<div class="block">
<?php foreach ($options['pager']->getResults() as $key => $result): ?>
<div class="ditem"><div class="item"><table><tbody><tr>
<td rowspan="<?php echo count($options['list'][$key]) + 1 ?>" class="photo">
<?php echo link_to(image_tag_sf_image($result->getImageFilename(), array('size' => '76x76')), sprintf($options['link_to_detail'], $result->getId())); ?><br />
<?php echo link_to(__('詳細を見る'), sprintf($options['link_to_detail'], $result->getId())) ?>
</td>
<th>
<?php
$keys = array_keys($raw_options['list'][$key]);
echo array_shift($keys);
?>
</th><td>
<?php echo array_shift($raw_options['list'][$key]); ?>
</td>
</tr>
<?php foreach ($raw_options['list'][$key] as $caption => $item) : ?>
<tr>
<th><?php echo $caption ?></th><td><?php echo $item ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endforeach; ?>
</div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($options['pager'], $options['link_to_page']); ?></p></div>
