以下のURLから<?php echo OpenPNEConfig::get('sns_name') ?>に登録してください。

<?php if ($isMobile) : ?>
<?php echo app_url_for('mobile_frontend', sprintf('mobileAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php else: ?>
<?php echo app_url_for('pc_frontend', sprintf('pcAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php endif; ?>
