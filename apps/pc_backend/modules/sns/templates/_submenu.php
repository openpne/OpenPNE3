<?php $categoryAttributes = sfConfig::get('openpne_sns_category_attribute'); ?>
<?php foreach (sfConfig::get('openpne_sns_category') as $category => $configs) :?>
<?php
if (!empty($categoryAttributes[$category]['Hidden']))
{
  continue;
}
$caption = !empty($categoryAttributes[$category]['Caption']) ? $categoryAttributes[$category]['Caption'] : $category;
?>
<li><?php echo link_to(__($caption), 'sns/config?category='.$category) ?></li>
<?php endforeach; ?>
<li><?php echo link_to(__('Term Configuration in this SNS'), 'sns/term') ?></li>
<li><?php echo link_to(__('Cache Clear'), 'sns/cache') ?></li>
<li><?php echo link_to(__('RichTextarea Configuration'), 'sns/richTextarea') ?></li>
