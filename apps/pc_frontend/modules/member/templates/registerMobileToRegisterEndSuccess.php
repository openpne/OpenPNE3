<?php ob_start() ?>
<p><?php echo __('%1%に参加するには、携帯電話の登録が必要です。', array('%1%' => $op_config['sns_name'])) ?></p>
<p><?php echo __('ここで入力した携帯メールアドレス宛に、携帯登録用のURLを送信します。') ?></p>
<ul>
<li><?php echo __('ここで入力したメールアドレスは他のメンバーには公開されません。') ?></li>
<li><?php echo __('ドメイン指定受信機能などをお使いの方は、携帯電話で「%1%」からのメールを受信できるように設定してください。', array('%1%' => $op_config['admin_mail_address'])) ?></li>
</ul>
<?php $partsInfo = ob_get_clean() ?>
<?php
$options = array(
  'title' => __('携帯電話の登録'),
  'partsInfo' => $partsInfo,
);
op_include_form('registerMobile', $form, $options);
?>
