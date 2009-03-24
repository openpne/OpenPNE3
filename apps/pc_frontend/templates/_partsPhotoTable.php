<?php if (count($options->list)): ?>

<?php
$options->setDefault('col', 5);
$options->setDefault('type', 'full');
$options->setDefault('crownIds', array());

$options->addRequiredOption('pager');
$options->addRequiredOption('link_to');
?>

<?php ob_start() ?>
<?php op_include_pager_navigation($options->pager, $options->getRaw('link_to_pager')) ?>
<?php $pager = ob_get_flush() ?>

<table>
<?php $row = ceil(count($options->list) / $options->col) ?>
<?php for ($i = $j = 1; $row >= $i; $i++): ?>
<?php if ($options->type === 'full' || $options->type === 'only_image'): ?>
<tr class="photo">
<?php for ($j = ($i * $options->col) - $options->col; ($i * $options->col) > $j; $j++): ?>
<td><?php if (!empty($options->list[$j])): ?>
<?php if (in_array($options->list[$j]->getId(), $options->getRaw('crownIds'))): ?>
<p class="crown"><?php echo image_tag('icon_crown.gif', array('alt' => 'admin')) ?></p>
<?php endif; ?>
<?php echo link_to(image_tag_sf_image($options->list[$j]->getImageFileName(), array('size' => '76x76')), $options->link_to.$options->list[$j]->getId()) ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php if ($options->type === 'full' || $options->type === 'only_name'): ?>
<tr class="text">
<?php for ($j = ($i * $options->col) - $options->col; ($i * $options->col) > $j; $j++): ?>
<td><?php if (!empty($options->list[$j])): ?>
<?php echo link_to($options->list[$j]->getNameAndCount(), $options->link_to.$options->list[$j]->getId()) ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php endfor; ?>
</table>
<?php echo $pager ?>
<?php endif; ?>
