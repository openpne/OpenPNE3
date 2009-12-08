<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('初期コミュニティ設定')); ?>

<p><?php echo __('メンバー新規登録時に以下で設定したコミュニティに自動的に参加させることができます。') ?></p>
<p><?php echo __('参加させたいコミュニティのIDを入力して「追加」ボタンを押してください。') ?></p>

<form action="<?php url_for('community/defaultCommunityList') ?>" method="post">
<table>
<?php echo $form ?>
<tr><td colspan="2"><input type="submit" value="追加"></td></tr>
</table>
</form>

<?php if ($communities): ?>
<table>
<tr>
<th>ID</th>
<th>コミュニティ名</th>
<th>管理者名</th>
<th>操作</th>
</tr>
<?php foreach ($communities as $community): ?>
<tr>
<td><?php echo $community->getId() ?></td>
<td><?php echo $community->getName() ?></td>
<td><?php echo $community->getAdminMember()->getName() ?></td>
<td><?php echo link_to('削除', 'community/removeDefaultCommunity?id='.$community->getId()) ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>
