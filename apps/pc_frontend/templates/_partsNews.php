<?php $rawList = $sf_data->getRaw('list') ?>

<table>
<tbody>
<?php $listCnt = 0 ?>
<?php foreach($list as $one_list): ?>
<tr>
<th><?php echo $rawList[$listCnt]['caption'] ?></th>
<td>

<ul class="articleList">
<?php foreach($one_list['content'] as $content): ?>
<li>
<span class="date"><?php echo date( __('m/d'), strtotime($content['date'])) ?></span>
<?php image_tag('articleList_maker.gif', array('alf' => '')) ?> 
<?php
if (isset($one_list['link_to_detail']))
{
  echo link_to($content['title'], sprintf($one_list['link_to_detail'], $content['id']));
}
else
{
  if (isset($content['link_to_external']))
  {
    echo '<a href="' . $content['link_to_texternal'] . '">' . $content['title'] . '</a>';
  }
}
?>
 (<?php echo $content['name'] ?>)
<?php if ($content['image']): ?>
<?php echo image_tag('icon_camera.gif', array( 'alt' => __('Those with a photograph'))) ?>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>

<?php if (isset($one_list['moreInfo'])): ?>
<div class="moreInfo"><ul class="moreInfo"><li>
<?php echo link_to(__('More info'), $one_list['moreInfo']) ?>
</li></ul></div>
<?php endif; ?>

</td>
</tr>

<?php $listCnt++ ?>
<?php endforeach; ?>
</tbody>
</table>
