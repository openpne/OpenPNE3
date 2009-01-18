<?php include_customizes($id, 'before') ?>
<div class="parts pageNav">
<?php include_customizes($id, 'top') ?>
<ul>
<?php include_customizes($id, 'listBegin') ?>
<?php foreach ($option['list'] as $key => $value) : ?>
<?php if ($key === $option['current']) : ?>
<li class="current">
<?php else : ?>
<li>
<?php endif; ?>
<?php echo $option['list']->getRaw($key) ?>
</li>
<?php endforeach; ?>
<?php include_customizes($id, 'listEnd') ?>
</ul>
<?php include_customizes($id, 'bottom') ?>
</div>
<?php include_customizes($id, 'after') ?>
