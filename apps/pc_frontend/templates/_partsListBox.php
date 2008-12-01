<?php include_customizes($id, 'before') ?>
<div class="dparts listBox"><div class="parts">

<?php if (isset($options['title'])) : ?>
<div class="partsHeading">
<?php include_customizes($id, 'headTop') ?>
<h3><?php echo $options['title'] ?></h3>
<?php include_customizes($id, 'headBottom') ?>
</div>
<?php endif; ?>

<table>
<?php foreach ($list as $key => $value): ?>
<tr>
<th><?php echo $key ?></th>
<td><?php echo $list->getRaw($key) ?></th>
</tr>
<?php endforeach; ?>
</table>

<?php if(isset($options['moreInfo'])) : ?>
<div class="block moreInfo">
<ul class="moreInfo">
<?php foreach ($options['moreInfo'] as $key => $value) : ?>
<li><?php echo $options['moreInfo']->getRaw($key); ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>

</div>
</div>
<?php include_customizes($id, 'after') ?>
