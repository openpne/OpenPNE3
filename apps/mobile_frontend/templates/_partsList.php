<table width="100%">
<?php if (empty($options['title'])): ?>
<tr><td bgcolor="<?php echo $op_color["core_color_7"] ?>">
<hr color="<?php echo $op_color["core_color_12"] ?>">
</td></tr>
<?php endif; ?>
<?php foreach ($options['list'] as $key => $value): ?>
<tr><td bgcolor="<?php echo cycle_vars($id, $op_color["core_color_6"].','.$op_color["core_color_7"]) ?>">
<?php echo $options['list']->getRaw($key) ?><br>
</td></tr>
<?php if (!empty($options['border'])): ?>
<tr><td bgcolor="<?php echo $op_color["core_color_7"] ?>">
<hr color="<?php echo $op_color["core_color_12"] ?>">
</td></tr>
<?php endif; ?>
<?php endforeach; ?>
</table>

