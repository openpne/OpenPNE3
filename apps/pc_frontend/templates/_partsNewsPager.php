<div class="<?php echo $id ?>">
<div class="dparts searchResultList"><div class="parts partsNewsPager">
<div class="partsHeading"><h3><?php echo $title ?></h3></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?></p></div>

<div class="block">
<?php foreach ($list as $res): ?>
<dl>
<dt><?php echo date( __('Y/m/d G:i'), strtotime($res['date'])) ?></dt>
<dd>
<?php
if (isset($link_to_detail))
{
  echo link_to($res['title'], sprintf($link_to_detail, $res['id']));
}
else
{
  if (isset($res['link_to_external']))
  {
    echo '<a href="' . $res['link_to_external'] . '">' . $res['title'] . '</a>';
  }
}
?>
 (<?php echo $res['name'] ?>)
<?php if ($res['image']): ?>
<?php echo image_tag( 'icon_camera.gif', array( 'alt' => __('Those with a photograph'))) ?>
<?php endif; ?>
</dd>
</dl>
<?php endforeach; ?>
</div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?></p></div>

</div></div>
</div>
