<h2>プロフィール項目設定</h2>

<h3>プロフィール項目一覧</h3>
<table>
<thead><tr>
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
<th>項目名</th>
<th>並び順（昇順）</th>
</tr></thead>
<?php foreach ($value->getProfileOptions() as $option) : ?>
<tr>
<td><?php echo $option->getId() ?></td>
<td><?php echo $option->getValue() ?></td>
<td><?php echo $option->getSortOrder() ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>
<?php endforeach; ?>
