<div id="<?php echo $id ?>" class="dparts homeRecentList">
<div class="parts">

<div class="partsHeading">
<h3><?php echo $options['title'] ?></h3>
</div>

<div class="block">

<ul class="articleList">
<?php foreach($options['list'] as $res): ?>
<li>
<span class="date"><?php echo date( __('m/d'), $res['date']) ?></span>
<?php image_tag('articleList_maker.gif', array('alf' => '')) ?> 
<?php
echo link_to( $res['title'], sprintf($options['link_to'], $res['id']));
?>
<?php if ($options['showName']): ?>
(<?php echo $res['name'] ?>)
<?php endif ?>
<?php if ($res['image']): ?>
<?php echo image_tag( 'icon_camera.gif', array( 'alt' => __('Those with a photograph'))) ?>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>

<?php if (isset($options['moreInfo'])): ?>
<div class="moreInfo"><ul class="moreInfo"><li>
<?php echo link_to(__('More info'), $options['moreInfo']) ?>
</li></ul></div>
<?php endif; ?>

</div>

</div></div>

