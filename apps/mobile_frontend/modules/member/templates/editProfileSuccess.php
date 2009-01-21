<?php op_mobile_page_title(__('Edit profile')) ?>

<form action="<?php echo url_for('member/editProfile') ?>" method="post">
<?php echo $memberForm ?>
<?php echo $profileForm ?>
<br><br>
<center><input type="submit" value="<?php echo __('Save') ?>"></center>
</form>
