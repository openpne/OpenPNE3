<?php include_page_title('設定変更') ?>
<form action="<?php echo url_for(sprintf('member/configComplete?token=%s&id=%s&type=%s', $sf_params->get('token'), $sf_params->get('id'), $sf_params->get('type'))) ?>" method="post">
<?php echo $form ?>
<br><br>
<center><input type="submit" value="送信"></center>
</form>
