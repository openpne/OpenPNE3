<?php if ($navs): ?>
<ul>
<?php foreach ($navs as $nav): ?>
<?php if (op_is_accessible_url($nav->uri)): ?>
<li id="globalNav_<?php echo op_url_to_id($nav->uri) ?>"><?php echo link_to($nav->caption, $nav->uri) ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
