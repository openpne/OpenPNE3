<?php if ($navs): ?>
<ul>
<?php foreach ($navs as $nav): ?>
<li><?php echo link_to($nav->getCaption(), $nav->getUri()) ?></li><?php endforeach; ?>

</ul>
<?php endif; ?>
