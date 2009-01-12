<?php slot('submenu'); ?>
<?php include_partial('submenu'); ?>
<?php end_slot(); ?>

<h2><?php echo __('招待メール送信') ?></h2>

<p><?php echo __('入力されたメールアドレス宛に「%1%」への招待状を送信します。', array('%1%' => $op_config['sns_name'])) ?></p>
<p><?php echo __('複数のメールアドレス宛にメールを送信する場合は、改行で区切って入力してください。') ?></p>

<form action="<?php echo url_for('member/invite') ?>" method="post">
<table>
<?php echo $form ?>
<tr>
<td colspan="2"><input type="submit" value="<?php echo __('送信') ?>" /></td>
</tr>
</table>
</form>
