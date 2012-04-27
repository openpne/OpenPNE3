<?php $snsName = $op_config['sns_name'] ?>
<?php echo __('Hello! This is information from %1%.', array('%1%' => $snsName)) ?>

<?php echo __('If you register(free) of member by the following URL,%br%you can participate in %1%.', array('%1%' => $snsName, '%br%' => "\n")) ?>

<?php echo __('* Participate in %1%', array('%1%' => $snsName)) ?>

<?php if ($isMobile) : ?>
<?php echo app_url_for('mobile_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>

<?php echo __('Can access this registration URL with mobile only.') ?>
<?php else: ?>
<?php echo app_url_for('pc_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>

<?php echo __('Can access this registration URL with pc only.') ?>
<?php endif; ?>
