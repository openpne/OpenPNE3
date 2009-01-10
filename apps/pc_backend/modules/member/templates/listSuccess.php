<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('メンバーリスト') ?></h2>

<form action="<?php echo url_for('member/list') ?>" method="get">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="検索" /></td>
</tr>
</table>
</form>

<?php if (!$pager->getNbResults()): ?>
<p><?php echo __('該当するメンバーは存在しません。') ?></p>
<?php else: ?>
<table>

<tr>
<td colspan="<?php echo 3 + count($profiles) + 2 ?>">
<?php echo pager_navigation($pager, 'member/list?page=%d', true, '?'.$sf_request->getCurrentQueryString()) ?>
</td>
</tr>

<tr>
<th><?php echo __('ID') ?></th>
<th><?php echo __('ニックネーム') ?></th>
<th><?php echo __('最終ログイン') ?></th>
<?php foreach ($profiles as $profile) : ?>
<th><?php echo $profile->getCaption() ?></th>
<?php endforeach; ?>
<th><?php echo __('PCメールアドレス') ?></th>
<th><?php echo __('携帯メールアドレス') ?></th>
</tr>

<?php foreach ($pager->getResults() as $i => $member): ?>
<tr style="background-color:<?php echo cycle_vars('member_list', '#fff, #eee') ?>;">
<td><?php echo $member->getId() ?></td>
<td><?php echo $member->getName() ?></td>
<td><?php if ($member->getLastLoginTime()) : ?><?php echo date('y-m-d<b\r />H:i:s', $member->getLastLoginTime()) ?><?php endif; ?></td>
<?php foreach ($profiles as $profile) : ?>
<td><?php echo $member->getProfile($profile->getName()); ?></td>
<?php endforeach; ?>
<td><?php echo $member->getConfig('pc_address') ?></td>
<td><?php echo $member->getConfig('mobile_address') ?></td>
</tr>
<?php endforeach; ?>

</table>
<?php endif; ?>
