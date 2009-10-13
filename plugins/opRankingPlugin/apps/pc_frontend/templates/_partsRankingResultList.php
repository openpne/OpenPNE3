<?php $list = $options->getRaw('list') ?>
<?php foreach ($options['model'] as $key => $model): ?>

<?php if (!$key || ($key > 0 && $options['rank'][$key] != $options['rank'][$key - 1])): ?>
<div class="dparts rankingList">
<div class="parts">
<?php if ($options['rank'][$key] == 1): ?>
<div class="partsHeading">
<h3><?php echo $options['title'] ?></h3>
</div>
<?php else: ?>
<div class="block">
<div class="ditem">
<div class="item">
<?php endif; ?>
<?php endif; ?>

<table>
<tbody>

<tr>
<td rowspan="<?php echo count($list[$key]) + 1 ?>" class="photo">
<?php
  echo link_to(
    image_tag_sf_image(
      $model->getImageFilename(),
      array('size' => $options['rank'][$key] == 1 ? '120x120' : '76x76')),
      sprintf($options['link_to_detail'],
      $model->getId()
    )
  );
?>
</td>

<?php $firstItem = true ?>
<?php foreach ($list[$key] as $caption => $item) : ?>
<?php echo $firstItem ? '' : '<tr>' ?>
<th><?php echo $caption ?></th>
<td <?php echo $firstItem ? 'class="name"' : '' ?>><?php echo $item ?></td>
</tr>
<?php $firstItem = false ?>
<?php endforeach; ?>

</tbody>
</table>

<?php if ($key == count($options['rank']) - 1 || $options['rank'][$key] != $options['rank'][$key + 1]): ?>
<?php if ($options['rank'][$key] != 1): ?>
</div></div></div>
<?php endif; ?>
</div></div>
<?php endif; ?>

<?php endforeach; ?>
