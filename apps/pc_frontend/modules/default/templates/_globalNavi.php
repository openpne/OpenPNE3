<?php if ($navis): ?>
<ul>
<?php foreach ($navis as $navi): ?>
<li><?php echo link_to($navi->getCaption(), $navi->getUri()) ?></li><?php endforeach; ?>

</ul>
<?php endif; ?>
