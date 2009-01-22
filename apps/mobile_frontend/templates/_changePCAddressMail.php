<?php echo __('Click following URL, please. You finish register if you inputs your password at here.') ?>
<?php echo __('If you already registered mail address, mail address is overwrited to new data.') ?>

<?php echo app_url_for('pc_frontend', 'member/configComplete?token='.$token.'&id='.$id.'&type='.$type, true) ?>
