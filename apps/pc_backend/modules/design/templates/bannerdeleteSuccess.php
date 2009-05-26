<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('バナー削除') ?></h2>
<p><?php echo __('本当に削除してもよろしいですか？') ?></p>
<form action="" method="post">
<td colspan="2"><input type="submit" value="<?php echo __('削除する') ?>" /></td>
</form>
