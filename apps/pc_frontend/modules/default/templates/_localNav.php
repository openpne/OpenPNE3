<?php if ($navs): ?>
<ul class="<?php echo $type; ?>">
<?php foreach ($navs as $nav): ?>

<?php if (isset($navId)): ?>
<?php $uri = $nav->uri.'?id='.$navId; ?>
<?php else: ?>
<?php $uri = $nav->uri; ?>
<?php endif; ?>

<?php if (op_is_accessable_url($uri)): ?>
<li><?php echo link_to($nav->caption, $uri); ?></li>
<?php endif; ?>

<?php endforeach; ?>
</ul>
<?php endif; ?>
