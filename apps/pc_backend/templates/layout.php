<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>

<title><?php echo OpenPNEConfig::get('sns_name') ?>管理画面</title>

</head>
<body id="<?php echo $sf_request->getParameter('module').'_'.$sf_request->getParameter('action') ?>">

<div id="header">
<h1><?php echo OpenPNEConfig::get('sns_name') ?>管理画面</h1>
</div>

<?php if ($sf_user->isAuthenticated()) : ?>
<div id="menu">
<ul>
<li><?php echo link_to(__('管理画面トップ'), 'security/top'); ?></li>
<li><?php echo link_to(__('SNS設定'), 'sns/config'); ?>
  <ul>
    <li><?php echo link_to(__('お知らせ設定'), 'sns/informationConfig'); ?></li>
  </ul>
</li>
<li><?php echo link_to(__('ナビ設定'), 'navi/index'); ?></li>
<li><?php echo link_to(__('プロフィール項目設定'), 'profile/list'); ?></li>
<li><?php echo link_to(__('プラグイン設定'), 'plugin/list'); ?></li>
</ul>
</div>
<?php endif; ?>

<div id="body">
<?php echo $sf_content ?>
</div>

</body>
</html>
