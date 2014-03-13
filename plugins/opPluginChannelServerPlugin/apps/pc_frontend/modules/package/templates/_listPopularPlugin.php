<?php if (count($plugins)): ?>
<div id="homeRecentList_<?php echo $gadget->getId() ?>" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Popular Plugins') ?></h3></div>
<div class="block">
<ul class="articleList">
<?php foreach ($plugins as $plugin): ?>
<li>
<?php echo link_to($plugin->name, 'package_home', $plugin) ?>
(<?php echo $plugin->user_count ?> users)
</li>
<?php endforeach; ?>
</ul>
</div>
</div></div>
<?php endif; ?>
