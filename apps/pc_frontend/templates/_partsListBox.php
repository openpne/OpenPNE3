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
<td><?php echo $value ?></th>
</tr>
<?php endforeach; ?>
</table>
</div>
<?php include_customizes($id, 'after') ?>
</div>
