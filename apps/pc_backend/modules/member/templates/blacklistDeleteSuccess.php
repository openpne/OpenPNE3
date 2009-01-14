<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('ブラックリスト削除') ?></h2>

<?php echo __('以下の内容を本当に削除しますか？') ?>

<form action="<?php echo url_for('member/blacklistDelete?id='.$blacklist->getId()) ?>" method="post">
<table>
<?php echo $form ?>
<tr><th><?php echo __('ID') ?></th><td><?php echo $blacklist->getId() ?></td></tr>
<tr><th><?php echo __('携帯電話個体識別番号（暗号化済）') ?></th><td><?php echo $blacklist->getUid() ?></td></tr>
<tr><th><?php echo __('メモ') ?></th><td><?php echo $blacklist->getMemo() ?></td></tr>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('削除') ?>" /></td>
</tr>
</table>
</form>
