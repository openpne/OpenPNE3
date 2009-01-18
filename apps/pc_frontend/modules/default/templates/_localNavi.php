<?php if ($navis): ?>
<ul>
<?php foreach ($navis as $navi): ?>
<li><?php if (isset($naviId)): ?>
<?php echo link_to($navi->getCaption(), $navi->getUri().'?id='.$naviId) ?>
<?php else: ?>
<?php echo link_to($navi->getCaption(), $navi->getUri()) ?>
<?php endif; ?></li><?php endforeach; ?>

</ul>
<?php endif; ?>
