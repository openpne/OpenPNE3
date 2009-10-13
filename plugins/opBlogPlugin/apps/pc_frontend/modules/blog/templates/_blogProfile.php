<?php if (count($blogList)): ?>

<tr>
<th><?php echo __('最新Blog') ?></th>

<td id="blogProfile">

<ul class="articleList">
<?php foreach($sf_data->getRaw('blogList') as $res): ?>
<li>
<span class="date"><?php echo date( __('m/d'), $res['date']) ?></span>
<?php image_tag('articleList_maker.gif', array('alf' => '')) ?>
<?php
echo '<a href="' . $res['link_to_external'] . '">' . $res['title'] . '</a>';
?>
</li>
<?php endforeach; ?>
</ul>

<div class="moreInfo"><ul class="moreInfo"><li>
<?php echo link_to(__('More info'), 'blog/profile?id=' . $id) ?>
</li></ul></div>

</td>
</tr>

<?php endif ?>
