<?php foreach (sfConfig::get('openpne_sns_category') as $category => $configs) :?>
<li><?php echo link_to($category, 'sns/config?category='.$category) ?></li>
<?php endforeach; ?>
<li><?php echo link_to(__('Term Configuration in this SNS'), 'sns/term') ?></li>
<li><?php echo link_to(__('Cache Clear'), 'sns/cache') ?></li>
