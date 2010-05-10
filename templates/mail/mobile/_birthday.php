<?php echo __('There is %1%\'s %my_friend% that its birthday is coming soon.', array('%1%' => $member->name)) ?>

<?php echo __('We suggest you to send birthday message to your friend.') ?>


<?php echo __('Birthday') ?> : <?php echo op_format_date(strtotime($birthMember->getProfile('op_preset_birthday')), 'XShortDateJa') ?>

<?php echo __('%nickname%', array('%nickname%' => $op_term['nickname']->titleize())) ?> : <?php echo $birthMember->name ?>

<?php echo __('URL') ?> : <?php echo sfConfig::get('op_base_url').app_url_for('mobile_frontend', '@member_profile?id='.$birthMember->id) ?>


<?php echo __('We hope that using %1% is useful for your future.', array('%1%' => $op_config['sns_name'])) ?>
