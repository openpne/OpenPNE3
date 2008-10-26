<?php include_page_title('ﾌﾟﾛﾌｨｰﾙ変更') ?>
<form action="<?php echo url_for('member/editProfile') ?>" method="post">
<?php echo $memberForm ?>
<?php echo $profileForm ?>
<br><br>
<center><input type="submit" value="変更する"></center>
</form>
