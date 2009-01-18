<table id="<?php echo $id ?>" width="100%">
<?php if (isset($option['title'])) : ?>
<tr><td bgcolor="#7ddadf">
<font color="#000000"><?php echo $option['title'] ?></font><br>
</td></tr>
<?php else : ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr>
<?php endif; ?>

<?php foreach ($contents as $key => $value) : ?>
<tr><td bgcolor="<?php echo cycle_vars($id, '#e0eaef,#ffffff') ?>">
<?php echo $contents->getRaw($key); ?><br>
</td></tr>
<?php if (!empty($option['border'])) : ?>
<tr><td bgcolor="#ffffff">
<hr color="#b3ceef">
</td></tr>
<?php endif; ?>
<?php endforeach; ?>

<?php if (isset($option['moreInfo'])) : ?>
<tr><td align="right">
<?php foreach ($option['moreInfo'] as $key => $value) : ?>
<?php echo $option['moreInfo']->getRaw($key); ?><br>
<?php endforeach; ?>
</td></tr>
<?php endif; ?>

</table>
<br>
