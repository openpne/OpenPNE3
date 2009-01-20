<ul>
<?php include_customizes($id, 'listBegin') ?>
<?php foreach ($options['list'] as $key => $value): ?>
<?php if ($key === $options['current']) : ?>
<li class="current">
<?php else: ?>
<li>
<?php endif; ?>
<?php echo $options['list']->getRaw($key) ?>
</li>
<?php endforeach; ?>
<?php include_customizes($id, 'listEnd') ?>
</ul>
