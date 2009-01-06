<?php $raw_option = $sf_data->getRaw('option'); ?>

<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('検索結果') ?></h3></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($option['pager'], $option['link_to_page']); ?></p></div>

<div class="block">
<?php foreach ($option['pager']->getResults() as $key => $result): ?>
<div class="ditem"><div class="item"><table><tbody><tr>
<td rowspan="<?php echo count($option['list'][$key]) + 1 ?>" class="photo">
<?php echo link_to(image_tag_sf_image($result->getImageFilename(), array('size' => '76x76')), sprintf($option['link_to_detail'], $result->getId())); ?><br />
<?php echo link_to(__('詳細を見る'), sprintf($option['link_to_detail'], $result->getId())) ?>
</td>
<th>
<?php
$keys = array_keys($raw_option['list'][$key]);
echo array_shift($keys);
?>
</th><td>
<?php echo array_shift($raw_option['list'][$key]); ?>
</td>
</tr>
<?php foreach ($raw_option['list'][$key] as $caption => $item) : ?>
<tr>
<th><?php echo $caption ?></th><td><?php echo $item ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endforeach; ?>
</div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($option['pager'], $option['link_to_page']); ?></p></div>

</div></div>
