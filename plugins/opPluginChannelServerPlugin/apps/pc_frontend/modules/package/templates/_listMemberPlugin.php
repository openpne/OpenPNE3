<div id="homeRecentList_<?php echo $gadget->getId() ?>" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Developing Plugin List') ?></h3></div>
<div class="block">
<?php if (count($plugins)): ?>
<ul class="articleList">
<?php foreach ($plugins as $plugin): ?>
<li>
<?php echo link_to($plugin->name, 'package_home', $plugin) ?>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<div class="moreInfo">
<ul class="moreInfo">
<li><?php echo link_to(__('More'), '@package_listMember?id='.$member->id) ?></li>
<?php if ($member->id == $sf_user->getMemberId()): ?>
<li><?php echo link_to(__('Create Plugin'), '@package_new') ?></li>
<?php endif; ?>
</ul>
</div>
</div>
</div></div>
