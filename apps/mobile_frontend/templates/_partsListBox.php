<?php include_customizes($id, 'before') ?>
<table id="<?php echo $id ?>" width="100%">
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
<tr><td bgcolor="<?php echo cycle_vars($id, '#e0eaef,#ffffff') ?>">
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
<?php include_customizes($id, 'after') ?>
