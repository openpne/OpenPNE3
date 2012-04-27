<?php echo __('We accepted your recovering password request.') ?>

<?php echo __('Please click the following URL and complete password recovery process.') ?>

<?php echo app_url_for('mobile_frontend', 'opAuthMailAddress/passwordRecoveryComplete?token='.$token.'&id='.$id, true) ?>
