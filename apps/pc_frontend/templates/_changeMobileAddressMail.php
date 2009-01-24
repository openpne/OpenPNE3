<?php echo __('Please click following URL, input the password, and complete registration.') ?>
<?php echo __('When the mail address is registered, it is changed to a new mail address.') ?>

<?php echo app_url_for('mobile_frontend', 'member/configComplete?token='.$token.'&id='.$id.'&type='.$type, true) ?>
