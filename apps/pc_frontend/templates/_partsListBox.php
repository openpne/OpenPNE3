<table>
<?php foreach ($content as $key => $value): ?>
<tr>
<th><?php echo $key ?></th>
<td><?php echo $content->getRaw($key) ?></td>
</tr>
<?php endforeach; ?>
<?php include_customizes($id, 'lastRow') ?>
</table>

<?php if (isset($options['moreInfo'])): ?>
<div class="block moreInfo">
<ul class="moreInfo">
<?php foreach ($options['moreInfo'] as $key => $value) : ?>
<li><?php echo $options['moreInfo']->getRaw($key); ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
