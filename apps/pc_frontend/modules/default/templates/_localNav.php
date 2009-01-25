<?php if ($navs): ?>
<ul class="<?php echo $type ?>">
<?php foreach ($navs as $nav): ?>
<li><?php if (isset($navId)): ?>
<?php echo link_to($nav->getCaption(), $nav->getUri().'?id='.$navId) ?>
<?php else: ?>
<?php echo link_to($nav->getCaption(), $nav->getUri()) ?>
<?php endif; ?></li><?php endforeach; ?>

</ul>
<?php endif; ?>
