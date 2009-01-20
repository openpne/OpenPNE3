<?php slot('op_mobile_header') ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo __('ﾌﾟﾛﾌｨｰﾙ変更') ?></a></font><br>
</td></tr>
</table>
<?php end_slot(); ?>
<form action="<?php echo url_for('member/editProfile') ?>" method="post">
<?php echo $memberForm ?>
<?php echo $profileForm ?>
<br><br>
<center><input type="submit" value="変更する"></center>
</form>
