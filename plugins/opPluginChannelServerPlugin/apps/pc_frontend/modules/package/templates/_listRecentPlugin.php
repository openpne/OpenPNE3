<?php if (count($plugins)): ?>
<div id="homeRecentList_<?php echo $gadget->getId() ?>" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Recently Created Plugins') ?></h3></div>
<div class="block">
<ul class="articleList">
<?php foreach ($plugins as $plugin): ?>
<li><span class="date"><?php echo op_format_date($plugin->created_at, 'XShortDateJa') ?></span>
<?php echo link_to($plugin->name, 'package_home', $plugin) ?>
</li>
<?php endforeach; ?>
</ul>
<div class="moreInfo">
<ul class="moreInfo">
<li><?php echo link_to(__('More'), '@package_search') ?></li>
</ul>
</div>
</div>
</div></div>
<?php endif; ?>
