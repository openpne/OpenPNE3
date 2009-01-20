<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo __('携帯電話を登録する') ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>

<?php echo __('個体識別番号の登録をおこないます。') ?><br>
<?php echo __('メンバー登録時に設定したパスワードを入力し、登録を完了してください。') ?><br>

<form action="<?php echo url_for('member/registerMobileToRegisterEnd?token='.$sf_params->get('token').'&id='.$sf_params->get('id')) ?>" method="post" utn>
<?php echo $form ?>
<input type="submit" value="<?php echo __('登録') ?>">
</form>
