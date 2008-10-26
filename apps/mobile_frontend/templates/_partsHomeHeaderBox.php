<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo $info['name'] ?><br>
</td>

<td valign="top">
<?php if (isset($menu['A'])) : ?>
<?php foreach ($menu['A'] as $key => $item) : ?>
<?php echo link_to($item, $key) ?><br>
<?php endforeach; ?>
<?php endif; ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<?php if (isset($menu['B'])) : ?>
<?php $is_first = true ?>
<?php foreach ($menu['B'] as $key => $item) : ?>
<?php if (!$is_first) {
  echo '/';
  $is_first = false;
} ?>
<?php echo link_to($item, $key) ?>
<?php endforeach; ?>
<?php endif; ?>
<hr color="#0d6ddf" size="3">
</td></tr>

<?php if (isset($menu['C'])) : ?>
<tr><td colspan="2">
<?php foreach ($menu['C'] as $key => $item) : ?>
<?php echo link_to($item, $key) ?><br>
<?php endforeach; ?>
<hr color="#0d6ddf" size="3">
</td></tr>
<?php endif; ?>

<?php if (isset($menu['D'])) : ?>
<tr><td colspan="2" align="center">
<?php $is_first = true ?>
<?php foreach ($menu['D'] as $key => $item) : ?>
<?php if (!$is_first) {
  echo '/';
  $is_first = false;
} ?>
<?php echo link_to($item, $key) ?>
<?php endforeach; ?>
<hr color="#0d6ddf" size="3">
</td></tr>
<?php endif; ?>

</table>
