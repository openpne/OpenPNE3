<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo '友人を'.$op_config['sns_name'].'に招待する' ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>

<form action="<?php echo url_for('member/invite') ?>" method="post">
<?php echo $form ?>
<br>
<input type="submit" value="<?php echo __('送信') ?>" />
</form>
