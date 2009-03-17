<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('ブラックリスト管理') ?></h2>

<h3><?php echo __('ブラックリストに追加') ?></h3>

<form action="<?php echo url_for('member/blacklist') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('追加') ?>" /></td>
</tr>
</table>
</form>

<h3><?php echo __('ブラックリスト一覧') ?></h3>

<?php if (!$pager->getNbResults()): ?>
<p><?php echo __('ブラックリストが登録されていません。') ?></p>
<?php else: ?>
<table>

<tr>
<td colspan="4">
<?php op_include_pager_navigation($pager, 'member/list?page=%d', true, '?'.$sf_request->getCurrentQueryString()) ?>
</td>
</tr>

<tr>
<th><?php echo __('ID') ?></th>
<th><?php echo __('携帯電話個体識別番号（暗号化済）') ?></th>
<th><?php echo __('メモ') ?></th>
<th><?php echo __('操作') ?></th>
</tr>

<?php foreach ($pager->getResults() as $blacklist): ?>
<tr style="background-color:<?php echo cycle_vars('member_list', '#fff, #eee') ?>;">
<td><?php echo $blacklist->getId() ?></td>
<td><?php echo $blacklist->getUid() ?></td>
<td><?php echo nl2br($blacklist->getMemo()) ?></td>
<td><?php echo link_to('削除', 'member/blacklistDelete?id='.$blacklist->getId()) ?></td>
</tr>
<?php endforeach; ?>

</table>
<?php endif; ?>
