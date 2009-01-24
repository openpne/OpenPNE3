<?php echo __('When mobile information registration is done from following URL, the member registration is completed.') ?>

<?php echo app_url_for('mobile_frontend', 'member/registerMobileToRegisterEnd?token='.$token.'&id='.$id, true) ?>
