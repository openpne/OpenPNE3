<?php use_helper('sfImage') ?>

<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('アプリケーション情報閲覧') ?></h2>

<h3>基本情報</h3>
<table>
<tr>
<td colspan="2"><?php echo image_tag_sf_image((string)$consumer->getImage(), array('size' => '180x180')) ?></td>
</tr>

<tr>
<th><?php echo __('名前') ?></th>
<td><?php echo $consumer->getName() ?></td>
</tr>

<tr>
<th><?php echo __('説明') ?></th>
<td><?php echo nl2br($consumer->getDescription()) ?></td>
</tr>

<tr>
<th><?php echo __('使用する API') ?></th>
<td>
<ul>
<?php foreach ($consumer->getAPICaptions() as $api) : ?>
  <li><?php echo $api ?></li>
<?php endforeach; ?>
</ul>
</td>
</tr>
</table>

<h3><?php echo __('連携に必要な情報') ?></h3>

<h4>Consumer key</h4>
<p><?php echo $consumer->getKeyString() ?></p>

<h4>Consumer secret</h4>
<p><?php echo $consumer->getSecret() ?></p>

<h4>Request token URL</h4>
<p><?php echo url_for('oauth_request_token', array(), true) ?></p>

<h4>Access token URL</h4>
<p><?php echo url_for('oauth_access_token', array(), true) ?></p>

<h4>Authorize URL</h4>
<p><?php echo url_for('oauth_authorize_token', array(), true) ?></p>

<h4><?php echo __('対応している署名方式') ?></h4>
<ul>
  <li>HMAC-SHA1</li>
</ul>
