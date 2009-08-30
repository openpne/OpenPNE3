<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('List of Connecting Application') ?></h2>

<table>
<tr>
<th><?php echo __('Image') ?></th>
<th><?php echo __('Application Name') ?></th>
<th><?php echo __('Operation') ?></th>
</tr>
<?php foreach ($consumers as $consumer): ?>
<tr>
<td><?php echo image_tag_sf_image((string)$consumer->getImage(), array('size' => '76x76')) ?></td>
<td><?php echo link_to($consumer->name, 'connection_show', $consumer) ?></td>
<td>
<ul>
<?php if ($consumer->getOAuthAdminAccessToken()): ?>
<li><?php echo link_to(__('Remove Admin Token'), 'connection/removeToken?id='.$consumer->id); ?></li>
<?php endif; ?>
<li><?php echo link_to(__('Edit'), 'connection/edit?id='.$consumer->id); ?></li>
<li><?php echo link_to(__('Delete'), 'connection/deleteConfirm?id='.$consumer->id); ?></li>
</ul>
</td>
</tr>
<?php endforeach; ?>
</table>
