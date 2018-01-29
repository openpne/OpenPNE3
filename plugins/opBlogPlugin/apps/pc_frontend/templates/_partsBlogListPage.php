<?php foreach ($options['blogRssCacheList'] as $blogRssCache): ?>
<dl>
<dt><?php echo op_format_date($blogRssCache->getDate(), 'XDateTimeJa') ?></dt>
<dd>
<?php echo link_to($blogRssCache->getTitle(), $blogRssCache->getLink()) ?>
<?php if ($options['showName']): ?>
(<?php echo $blogRssCache->getMember()->getName() ?>)
<?php endif ?>
</dd>
</dl>
<?php endforeach; ?>
