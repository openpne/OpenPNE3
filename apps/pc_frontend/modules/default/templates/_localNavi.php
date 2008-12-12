<?php if ($navis) : ?>
<ul>
<?php foreach ($navis as $navi) : ?>
<?php if (isset($naviId)) : ?>
<li><?php echo link_to($navi->getCaption(), $navi->getUri().'?id='.$naviId) ?></li>
<?php else : ?>
<li><?php echo link_to($navi->getCaption(), $navi->getUri()) ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
