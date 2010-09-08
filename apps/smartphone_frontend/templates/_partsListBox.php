<table>
<?php include_customizes($id, 'firstRow') ?>
<?php foreach ($options['list'] as $key => $value): ?>
<tr>
<th><?php echo $key ?></th>
<td><?php echo $options['list']->getRaw($key) ?></td>
</tr>
<?php endforeach; ?>
<?php include_customizes($id, 'lastRow') ?>
</table>
