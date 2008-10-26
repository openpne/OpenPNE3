<table width="100%">
<?php if (isset($options['title'])) : ?>
<tr><td bgcolor="#7ddadf">
<font color="#000000"><?php echo $options['title'] ?></font><br>
</td></tr>
<?php endif; ?>

<?php foreach ($list as $key => $value) : ?>
<tr><td bgcolor="#e0eaef">
<?php
echo $list->getRaw($key).'<br>';
?>
</td></tr>
<?php endforeach; ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr></table>
<br>
