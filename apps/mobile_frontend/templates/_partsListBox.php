<table id="<?php echo $id ?>" width="100%">
<?php if (isset($options['title'])): ?>
<tr><td bgcolor="<?php echo $op_color["core_color_5"] ?>">
<font color="<?php echo $op_color["core_color_25"] ?>"><?php echo $options['title'] ?></font><br>
</td></tr>
<?php else: ?>
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

<?php if (isset($options['moreInfo'])): ?>
<tr><td align="right">
<?php foreach ($options['moreInfo'] as $key => $value): ?>
<?php echo $options['moreInfo']->getRaw($key) ?><br>
<?php endforeach; ?>
</td></tr>
<?php endif; ?>

</table>
<br>
