<h2><?php echo __('連携済みアプリケーション一覧') ?></h2>

<table>
<tr>
<th><?php echo __('画像') ?></th>
<th><?php echo __('アプリケーション名') ?></th>
<th><?php echo __('操作') ?></th>
</tr>
<?php foreach ($consumers as $consumer): ?>
<tr>
<td><?php echo image_tag_sf_image((string)$consumer->getImage()) ?></td>
<td><?php echo link_to($consumer->name, 'connection/show?id='.$consumer->id) ?></td>
<td>
<ul>
<li><?php echo link_to(__('Edit'), 'connection/edit?id='.$consumer->id); ?></li>
</ul>
</td>
</tr>
<?php endforeach; ?>
</table>

<?php echo link_to(__('Add new application'), 'connection/register'); ?>

