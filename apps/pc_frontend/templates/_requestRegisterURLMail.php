以下のURLから<?php echo OpenPNEConfig::get('sns_name') ?>に登録してください。

<?php echo url_for(sprintf('pcAddress/register?token=%s&authMode=%s', $token, $authMode), true) ?>
