<?php
op_include_parts('memberImageBox', 'consumerImageBox', array(
  'object' => $consumer,
  'name_method' => 'getName',
));
?>

<?php slot('_api_list'); ?>
<ul>
<?php foreach ($consumer->getAPICaptions() as $api) : ?>
  <li><?php echo $api ?></li>
<?php endforeach; ?>
</ul>
<?php end_slot(); ?>

<?php
op_include_parts('listBox', 'consumerInformation', array(
  'list' => array(
    __('説明') => nl2br($consumer->getDescription()),
    __('使用する API') => get_slot('_api_list'),
    __('Consumer key') => $consumer->getKeyString(),
    __('Consumer secret') => $consumer->getSecret(),
    __('Request token URL') => url_for('oauth_request_token', array(), true),
    __('Access token URL') => url_for('oauth_access_token', array(), true),
    __('Authorize URL') => url_for('oauth_authorize_token', array(), true),
   __('対応している署名方式') => 'HMAC-SHA1',
)));
?>

<ul>
  <li><?php echo link_to('このアプリケーションを編集', 'connection_edit', $consumer) ?></li>
  <li><?php echo link_to('連携済みアプリケーション一覧', 'connection_list') ?></li>
</ul>
