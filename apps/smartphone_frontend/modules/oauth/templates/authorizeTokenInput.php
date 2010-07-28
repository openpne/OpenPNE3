<?php slot('_body'); ?>
<p><?php echo __('外部アプリケーション「%1%」があなたのデータ（情報）へのアクセスを要求しています。', array('%1%' => link_to($information->getConsumer()->getName(), 'connection_show', $information->getConsumer()))); ?></p>
<p><?php echo __('このアプリケーションは、あなたの権限を借りて以下に示すことをおこなう可能性があります。') ?></p>

<textarea rows="5" cols="60" readonly="readonly">
<?php foreach ($information->getConsumer()->getAPICaptions() as $api): ?>
<?php echo $api."\n" ?>
<?php endforeach; ?>
</textarea>

<p><?php echo __('このアプリケーションが信頼できない場合、許可をおこなわないでください。') ?></p>
<p><?php echo __('データ提供を中止したい場合は、設定変更画面にて、このアプリケーションを無効にしてください。') ?></p>
<p><?php echo __('「%1%」のアクセスを許可しますか？', array('%1%' => $information->getConsumer()->getName())) ?></p>
<?php end_slot(); ?>

<?php slot('_yes_form'); ?>
<input type="hidden" name="oauth_token" value="<?php echo $token ?>" />
<input type="hidden" name="allow" value="1" />
<?php end_slot(); ?>
<?php slot('_no_form'); ?>
<input type="hidden" name="oauth_token" value="<?php echo $token ?>" />
<?php end_slot(); ?>

<?php
op_include_parts('consentForm', 'oauthAuthorizeTokenForm', array(
  'title'                => __('アプリケーション許可設定'),
  'body'                 => get_slot('_body'),
  'yes_form'             => get_slot('_yes_form'),
  'no_form'              => get_slot('_no_form'),
  'consent_from'         => $op_config['sns_name'],
  'consent_to'           => $information->getConsumer()->getName(),
  'allow_image_filename' => 'consent_allow2.gif',
))
?>
