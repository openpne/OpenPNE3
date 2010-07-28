<?php op_include_pager_navigation($pager, '@friend_list?page=%d&id=' . $sf_params->get('id')); ?>
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
<?php echo op_image_tag( 'icon_camera.gif', array( 'alt' => __('Those with a photograph'))) ?>
<?php endif; ?>
</dd>
</dl>
<?php endforeach; ?>
</div>

<?php op_include_pager_navigation($pager, '@friend_list?page=%d&id=' . $sf_params->get('id')); ?>
