<?php slot('submenu'); ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('ログイン停止設定') ?></h2>

<p><?php echo format_number_choice(
    '[0]ログイン停止設定を有効にします。|[1]ログイン停止設定を解除します。', array(), $member->getIsLoginRejected()
  )
?></p>

<form action="<?php echo url_for('member/reject?id='.$member->getId()) ?>" method="post">
<table>
<tr>
<th><?php echo __('ID') ?></th><td><?php echo $member->getId() ?></td>
</tr>
<tr>
<th><?php echo __('ニックネーム') ?></th><td><?php echo $member->getName() ?></td>
</tr>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo format_number_choice(
    '[0]有効にする|[1]解除する', array(), $member->getIsLoginRejected()
  )
?>" /></td>
</tr>
</table>
</form>

<?php use_helper('Javascript') ?>
<?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?>
