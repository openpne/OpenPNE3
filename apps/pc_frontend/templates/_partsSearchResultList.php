<div class="pagerRelative"><p class="number"><?php echo pager_navigation($options['pager'], $options['link_to_page']); ?></p></div>

<div class="block">
<?php foreach ($options['pager']->getResults() as $key => $result): ?>
<?php $list = $options->list->getRaw($key); ?>
<div class="ditem"><div class="item"><table><tbody><tr>
<td rowspan="<?php echo count($options['list'][$key]) + 1 ?>" class="photo">
<?php echo link_to(image_tag_sf_image($result->getImageFilename(), array('size' => '76x76')), sprintf($options['link_to_detail'], $result->getId())); ?><br />
<?php echo link_to(__('詳細を見る'), sprintf($options['link_to_detail'], $result->getId())) ?>
</td>
<th>
<?php
reset($list);
echo key($list);
?>
</th><td>
<?php echo array_shift($list); ?>
</td>
</tr>
<?php foreach ($list as $caption => $item) : ?>
<tr>
<th><?php echo $caption ?></th><td><?php echo $item ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endforeach; ?>
</div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($options['pager'], $options['link_to_page']); ?></p></div>
