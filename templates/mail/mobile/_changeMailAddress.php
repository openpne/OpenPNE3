<?php echo __('Click following URL, please. You finish register if you inputs your password at here.') ?>
<?php echo __('If the mail address is registered, it is changed to a new mail address.') ?>

<?php echo app_url_for('mobile_frontend', 'member/configComplete?token='.$token.'&id='.$id.'&type='.$type, true) ?>
