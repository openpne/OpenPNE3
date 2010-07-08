<?php slot('pager') ?>
<?php op_include_pager_navigation($options['pager'], $options['link_to_page'], array('use_current_query_string' => true)); ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>

<div class="block">
<?php foreach ($options['pager']->getResults() as $key => $result): ?>
<?php $list = $options->list->getRaw($key); ?>
<div class="ditem"><div class="item"><table><tbody><tr>
<td rowspan="<?php echo count($options['list'][$key]) + 1 ?>" class="photo">
<?php echo link_to(op_image_tag_sf_image($result->getImageFilename(), array('size' => '76x76')), sprintf($options['link_to_detail'], $result->getId())); ?><br />
<?php echo link_to(__('Details'), sprintf($options['link_to_detail'], $result->getId())) ?>
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
<th><?php echo $caption ?></th><td><?php echo op_truncate($item, 36, '', 3) ?></td>
</tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endforeach; ?>
</div>

<?php include_slot('pager') ?>
