以下のURLから<?php echo $op_config['sns_name'] ?>に登録してください。

<?php if ($isMobile) : ?>
<?php echo app_url_for('mobile_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php else: ?>
<?php echo app_url_for('pc_frontend', sprintf('opAuthMailAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
<?php endif; ?>
