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
$list = array();
if (op_have_privilege('edit'))
{
  $list = array(
    __('Consumer key') => $consumer->getKeyString(),
    __('Consumer secret') => $consumer->getSecret(),
    __('Request token URL') => url_for('oauth_request_token', array(), true),
    __('Access token URL') => url_for('oauth_access_token', array(), true),
    __('Authorize URL') => url_for('oauth_authorize_token', array(), true),
    __('対応している署名方式') => 'HMAC-SHA1',
  );
}

op_include_parts('listBox', 'consumerInformation', array(
  'list' => array_merge(array(
    __('説明')         => nl2br($consumer->getDescription()),
    __('登録者')       => $consumer->getMember(),
    __('使用する API') => get_slot('_api_list'),
  ), $list)));
?>

<ul>
<?php if (op_have_privilege('edit')): ?>
  <li><?php echo link_to('このアプリケーションを編集', 'connection_edit', $consumer) ?></li>
<?php endif; ?>
  <li><?php echo link_to('連携済みアプリケーション一覧', 'connection_list') ?></li>
</ul>
