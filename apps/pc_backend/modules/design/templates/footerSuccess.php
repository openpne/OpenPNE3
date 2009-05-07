<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('フッター設定') ?></h2>

<p><?php echo __('※「設定変更」ボタンを押すと設定が反映されます。') ?></p>

<ul>
<li><?php echo link_to('ログイン前フッター設定', 'design/footer?type=before') ?></li>
<li><?php echo link_to('ログイン後フッター設定', 'design/footer?type=after') ?></li>
</ul>

<?php if ($type == 'after'): ?>
<h3><?php echo __('ログイン後フッター') ?></h3>
<?php else: ?>
<h3><?php echo __('ログイン前フッター') ?></h3>
<?php endif; ?>

<form action="<?php echo url_for('design/footer?type=' . $type) ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('設定変更') ?>" /></td>
</tr>
</table>
</form>
