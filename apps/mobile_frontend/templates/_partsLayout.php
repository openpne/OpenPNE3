<?php include_customizes($id, 'before'); ?>
<div id="<?php echo $id ?>" />
<?php if (isset($options['title']) && $options['title'] !== ''): ?>
<table width="100%">
<tr><td bgcolor="#7ddadf">
<font color="#000000"><?php echo $options['title'] ?></font><br>
</td></tr>
</table>
<?php endif; ?>
<?php if (isset($options['partsInfo'])): ?>
<?php echo $options->getRaw('partsInfo') ?>
<?php endif; ?>
<?php include_customizes($id, 'top'); ?>
<?php echo $sf_data->getRaw('op_content'); ?>
<?php include_customizes($id, 'bottom'); ?>
<?php if (isset($options['moreInfo'])): ?>
<div align="right">
<?php foreach ($options['moreInfo'] as $key => $value): ?>
<font color="#0c5f0f">⇒</font><?php echo $options['moreInfo']->getRaw($key) ?><br>
<?php endforeach; ?>
</div>
<?php endif; ?>
<br>
</div>
<?php include_customizes($id, 'after') ?>
