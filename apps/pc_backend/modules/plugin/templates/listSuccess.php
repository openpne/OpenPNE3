<?php if ($plugins) : ?>
<table>
<tr>
<th><?php echo __('有効/無効') ?></th>
<th><?php echo __('プラグイン名') ?></th>
<th><?php echo __('バージョン') ?></th>
<th><?php echo __('プラグインの説明') ?></th>
</tr>
<?php foreach ($plugins as $plugin) : ?>
<tr>
<td><?php echo $plugin->getIsActive() ?></td>
<td><?php echo $plugin->getName() ?></td>
<td><?php echo $plugin->getVersion() ?></td>
<td><?php echo $plugin->getSummary() ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
