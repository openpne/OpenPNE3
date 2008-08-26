<?php if ($navis) : ?>
<ul>
<?php foreach ($navis as $navi) : ?>
<?php if ($sf_request->hasParameter('id')) : ?>
<li><?php echo link_to($navi->getCaption(), $navi->getUri() . '?id=' . $sf_request->getParameter('id')) ?></li>
<?php else : ?>
<li><?php echo link_to($navi->getCaption(), $navi->getUri()) ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
