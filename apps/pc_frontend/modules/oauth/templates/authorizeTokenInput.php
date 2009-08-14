<?php slot('body'); ?>
<p><?php echo __('アプリケーション「%1%」がリソースへのアクセスを希望しています。', array('%1%' => $information->getConsumer()->getName())); ?></p>
<p><?php echo __('このアプリケーションは、あなたの権限を借りて以下に示すことをおこなう可能性があります。') ?></p>

<textarea rows="5" cols="60" readonly="readonly">
<?php foreach ($information->getConsumer()->getAPICaptions() as $api): ?>
<?php echo $api."\n" ?>
<?php endforeach; ?>
</textarea>

<p><?php echo __('許可しますか？') ?></p>
<?php end_slot(); ?>

<?php slot('form'); ?>
<input type="hidden" name="oauth_token" value="<?php echo $token ?>" />
<input type="hidden" name="allow" value="1" />
<?php end_slot(); ?>

<?php
op_include_parts('yesNo', 'deleteConfirmForm', array(
  'title'    => __('アプリケーション許可設定'),
  'body'     => get_slot('body'),
  'yes_form' => get_slot('form'),
))
?>
