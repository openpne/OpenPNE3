<table>
<tbody>
<?php foreach($list as $one_list): ?>
<tr>
<th><?php echo $one_list['caption'] ?></th>
<td>

<ul>
<?php foreach($one_list['content'] as $content): ?>
<li>
<span><?php echo date( __('m/d'), strtotime($content['date'])) ?></span>
 ... 
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
<img alt="<?php echo __('Those with a photograph') ?>" src="/images/icon_camera.gif" />
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
<?php endforeach; ?>
</tbody>
</table>
