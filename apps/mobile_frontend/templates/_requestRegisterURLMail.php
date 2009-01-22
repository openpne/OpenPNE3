<?php echo __('Click following URL, please. You can register in %1%.', array('%1%' => $op_config['sns_name'])) ?>

<?php if ($isMobile) : ?>
<?php echo app_url_for('mobile_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php else: ?>
<?php echo app_url_for('pc_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php endif; ?>
