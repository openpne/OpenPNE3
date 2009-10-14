<?php echo __('Hello! This is information from %1%.', array('%1%' => $op_config['sns_name'])) ?>


<?php echo __('%1% registered your %community%, "%2%".', array('%1%' => $new_member->name, '%2%' => $community->name)) ?>


<?php echo __('"%1%" %community% page:', array('%1%' => $community->name)) ?>

<?php echo app_url_for('pc_frontend', 'community/home?id='.$community->id, true) ?>


<?php echo __('"%1%"\'s profile page:', array('%1%' => $new_member->name)) ?>

<?php echo app_url_for('pc_frontend', 'member/profile?id='.$new_member->id, true) ?>
