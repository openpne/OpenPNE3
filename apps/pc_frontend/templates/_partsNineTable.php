<?php if (count($options->list)): ?>

<?php
$options->setDefault('row', 3);
$options->setDefault('col', 3);
$options->setDefault('type', 'full');
$options->setDefault('crownIds', array());
$options->setDefault('use_op_link_to_member', false);
?>
<table>
<?php $row = ceil(count($options->list) / $options->row) ?>
<?php for ($i = $j = 1; $row >= $i; $i++): ?>
<?php if ($options->type === 'full' || $options->type === 'only_image'): ?>
<tr class="photo">
<?php for ($j = ($i * $options->col) - $options->col; ($i * $options->col) > $j; $j++): ?>
<td><?php if (!empty($options->list[$j])): ?>
<?php if (in_array($options->list[$j]->getId(), $options->getRaw('crownIds'))): ?>
<p class="crown"><?php echo op_image_tag('icon_crown.gif', array('alt' => 'admin')) ?></p>
<?php endif; ?>
<?php if ($options->use_op_link_to_member): ?>
<?php echo op_link_to_member($options->list[$j], array('link_target' => op_image_tag_sf_image($options->list[$j]->getImageFileName(), array('size' => '76x76')))) ?>
<?php else: ?>
<?php echo link_to(op_image_tag_sf_image($options->list[$j]->getImageFileName(), array('size' => '76x76')), $options->link_to.$options->list[$j]->getId()) ?>
<?php endif; ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php if ($options->type === 'full' || $options->type === 'only_name'): ?>
<tr class="text">
<?php for ($j = ($i * $options->col) - $options->col; ($i * $options->col) > $j; $j++): ?>
<td><?php if (!empty($options->list[$j])): ?>
<?php if ($options->use_op_link_to_member): ?>
<?php echo op_link_to_member($options->list[$j], array('link_target' => $options->list[$j]->getNameAndCount())) ?>
<?php else: ?>
<?php echo link_to($options->list[$j]->getNameAndCount(), $options->link_to.$options->list[$j]->getId()) ?>
<?php endif; ?>
<?php endif; ?></td>
<?php endfor; ?>
</tr>
<?php endif; ?>
<?php endfor; ?>
</table>
<?php endif; ?>
