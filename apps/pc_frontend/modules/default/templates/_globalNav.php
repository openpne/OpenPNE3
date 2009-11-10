<?php if ($navs): ?>
<ul>
<?php foreach ($navs as $nav): ?>
<?php if (op_is_accessable_url($nav->uri)): ?>
<li><?php echo link_to($nav->caption, $nav->uri) ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
