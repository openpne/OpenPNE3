<h2>プロフィール項目設定</h2>

<h3>プロフィール項目一覧</h3>
<p><?php echo link_to('プロフィール項目登録', 'profile/edit') ?></p>
<table>
<thead><tr>
<th colspan="2">操作</th>
<th>ID</th>
<th>項目名</th>
<th>識別名</th>
<th>必須</th>
<th>重複の可否</th>
<th>フォームタイプ</th>
<th>並び順（昇順）</th>
<th>選択肢</th>
<th>登録</th>
<th>変更</th>
<th>検索</th>
</tr></thead>
<?php foreach ($profiles as $value): ?>
<tr>
<td><?php echo link_to('変更', 'profile/edit?id=' . $value->getId()) ?></td>
<td><?php echo link_to('削除', 'profile/delete?id=' . $value->getId()) ?></td>
<td><?php echo $value->getId() ?></td>
<td><?php echo $value->getCaption() ?></td>
<td><?php echo $value->getName() ?></td>
<td><?php echo ($value->getIsRequired() ? '○' : '×') ?></td>
<td><?php echo ($value->getIsUnique() ? '×' :'○') ?></td>
<td><?php echo $value->getFormType() ?></td>
<td><?php echo $value->getSortOrder() ?></td>
<td></td>
<td><?php echo ($value->getIsDispRegist() ? '○' : '×') ?></td>
<td><?php echo ($value->getIsDispConfig() ? '○' : '×') ?></td>
<td><?php echo ($value->getIsDispSearch() ? '○' : '×') ?></td>
</tr>
<?php endforeach; ?>
</table>

<h3>プロフィール選択肢一覧</h3>
<?php foreach ($profiles as $value): ?>
<?php if ($value->countProfileOptions()) : ?>

<h4><?php echo $value->getCaption() ?></h4>
<table>
<thead><tr>
<th>ID</th>
<th>項目名(ja_JP)</th>
<th>並び順（昇順）</th>
<th>操作</th>
</tr></thead>
<?php foreach ($value->getProfileOptions() as $option) : ?>
<form action="<?php echo url_for('profile/editOption?id=' . $option->getId()) ?>" method="post">
<tr>
<td><?php echo $option->getId() ?></td>
<td>
<?php echo $option_form[$value->getId()][$option->getId()]['ja_JP']['value']->render() ?>
</td>
<td>
<?php echo $option_form[$value->getId()][$option->getId()]['sort_order']->render() ?>
</td>
<td>
<?php echo $option_form[$value->getId()][$option->getId()]['id']->render() ?>
<input type="submit" value="変更" />
</td>
</form>
</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>
<?php endforeach; ?>
