<?php if ($result): ?>
<div id="homeRecentList_<?php echo $gadget->getId() ?>" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo  $gadget->getConfig('title') ? $gadget->getConfig('title') : $result[0]; ?></h3></div>
<div class="block">
<ul class="articleList">
<?php foreach ($result[1] as $entry): ?>
<?php if ('' === $entry['date']): ?>
<?php   $dateStr = __("Unknown Day");?>
<?php else: ?>
<?php   $dateStr = op_format_date($entry['date'], 'XShortDateJa');?>
<?php endif; ?>
<li><span class="date"><?php echo $dateStr ?></span>
<?php echo link_to($entry['title'], $entry['link']) ?>
</li>
<?php endforeach; ?>
</ul>
</div>
</div></div>
<?php endif; ?>
