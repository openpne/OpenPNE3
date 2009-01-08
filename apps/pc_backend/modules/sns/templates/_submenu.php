<?php foreach (sfConfig::get('openpne_sns_category') as $category => $configs) :?>
<li><?php echo link_to($category, 'sns/config?category='.$category) ?></li>
<?php endforeach; ?>
