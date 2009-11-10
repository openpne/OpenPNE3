<?php foreach ($menu as $item): ?>
<li><?php echo link_to(__($item['caption']), $item['url']); ?></li>
<?php endforeach; ?>
