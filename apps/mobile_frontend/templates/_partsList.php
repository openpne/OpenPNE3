<table width="100%">
<?php if (empty($options['title'])): ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr>
<?php endif; ?>
<?php foreach ($options['list'] as $key => $value): ?>
<tr><td bgcolor="<?php echo cycle_vars($id, '#e0eaef,#ffffff') ?>">
<?php echo $options['list']->getRaw($key) ?><br>
</td></tr>
<?php if (!empty($options['border'])): ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr>
<?php endif; ?>
<?php endforeach; ?>
</table>

