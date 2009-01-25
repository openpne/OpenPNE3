<?php if (count($options['list'])): ?>

<?php
$options->setDefault('row', 10);
$options->setDefault('col', 5);
$options->setDefault('type', 'full');
?>

<?php ob_start() ?>
<?php include_partial('global/pagerRelative', array('pager' => $options->pager, 'link_to' => $options->getRaw('link_to_pager'))) ?>
<?php $pager = ob_get_flush() ?>

<table>
<?php $row = ceil(count($options['list']) / $options['row']) ?>
<?php for ($i = $j = 1; $row >= $i; $i++): ?>
<?php if ($options['type'] === 'full' || $options['type'] === 'only_image'): ?>
<tr class="photo">
<?php for ($j = ($i * $options['col']) - $options['col']; ($i * $options['col']) > $j; $j++): ?>
<td><?php if (!empty($options['list'][$j])): ?>
<?php echo link_to(image_tag_sf_image($options['list'][$j]->getImageFileName(), array('size' => '76x76')), $options['link_to'].$options['list'][$j]->getId()) ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php if ($options['type'] === 'full' || $options['type'] === 'only_name'): ?>
<tr class="text">
<?php for ($j = ($i * $options['col']) - $options['col']; ($i * $options['col']) > $j; $j++): ?>
<td><?php if (!empty($options['list'][$j])): ?>
<?php echo link_to($options['list'][$j]->getName(), 'member/profile?id='.$options['list'][$j]->getId()) ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php endfor; ?>
</table>
<?php endif; ?>

<?php echo $pager ?>
