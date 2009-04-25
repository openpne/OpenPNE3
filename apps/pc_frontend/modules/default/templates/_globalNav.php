<?php if ($navs): ?>
<ul>
<?php foreach ($navs as $nav): ?>
<li><?php echo link_to($nav->caption, $nav->uri) ?></li><?php endforeach; ?>

</ul>
<?php endif; ?>
