<table width="100%">
<?php if (isset($options['title'])) : ?>
<tr><td bgcolor="#7ddadf">
<font color="#000000"><?php echo $options['title'] ?></font><br>
</td></tr>
<?php else : ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr>
<?php endif; ?>

<?php foreach ($list as $key => $value) : ?>
<tr><td bgcolor="#e0eaef">
<?php echo $list->getRaw($key); ?><br>
</td></tr>
<?php if (!empty($options['border'])) : ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr>
<?php endif; ?>
<?php endforeach; ?>

<?php if (isset($options['moreInfo'])) : ?>
<tr><td align="right">
<?php foreach ($options['moreInfo'] as $key => $value) : ?>
<?php echo $options['moreInfo']->getRaw($key); ?><br>
<?php endforeach; ?>
</td></tr>
<?php endif; ?>

</table>
<br>
