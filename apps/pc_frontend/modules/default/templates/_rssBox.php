<?php if ($result): ?>
<div id="homeRecentList_<?php echo $gadget->getId() ?>" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo  $gadget->getConfig('title') ? $gadget->getConfig('title') : $result[0]; ?></h3></div>
<div class="block">
<ul class="articleList">
<?php if ($gadget->getConfig('is_show_detail')): ?>
<?php $res = $sf_data->getRaw('result') ?>
<?php else: ?>
<?php $res = $result ?>
<?php endif; ?>
<?php foreach ($res[1] as $entry): ?>
<?php if ('' === $entry['date']): ?>
<?php   $dateStr = __("Unknown Day");?>
<?php else: ?>
<?php   $dateStr = op_format_date($entry['date'], 'XShortDateJa');?>
<?php endif; ?>
<li><span class="date"><?php echo $dateStr ?></span>
<?php echo link_to($entry['title'], $entry['link']) ?>
<?php if ($gadget->getConfig('is_show_detail')): ?>
<?php echo nl2br($entry['body']) ?>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
<?php if ($gadget->getConfig('is_show_more')): ?>
<div class="more-rss-contents">
<?php echo link_to(__("More"), $gadget->getConfig('more_url'), 'target=_blank') ?>
</div>
<?php endif; ?>
</div>
</div>
</div>
<?php endif; ?>
