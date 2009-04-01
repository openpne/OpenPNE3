<table>
<tr><th>ID</th><td><?php echo $community->getId() ?></td></tr>
<tr><th><?php echo __('コミュニティ名') ?></th><td><?php echo $community->getName() ?></td></tr>
<tr><th><?php echo __('カテゴリ') ?></th><td><?php echo $community->getCommunityCategory() ?></td></tr>
<tr><th><?php echo __('管理者名') ?></th><td><?php echo $community->getAdminMember()->getName() ?></td></tr>
<tr><th><?php echo __('メンバー数') ?></th><td><?php echo $community->countCommunityMembers() ?></td></tr>
<tr><th><?php echo __('作成日') ?></th><td><?php echo $community->getCreatedAt() ?></td></tr>
<?php foreach ($community->getConfigs() as $name => $config): ?>
<tr><th><?php echo __($name, array(), 'form_community') ?></th><td><?php echo nl2br($config) ?></td></tr>
<?php endforeach; ?>
<?php if ($moreInfo): ?>
<tr><td colspan="2">
<ul>
<?php foreach ($sf_data->getRaw('moreInfo') as $more): ?>
<li><?php echo $more ?></li>
<?php endforeach; ?>
</ul>
</td></tr>
<?php endif; ?>
</table>

