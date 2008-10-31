<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>

<title><?php echo OpenPNEConfig::get('sns_name') ?>管理画面</title>

<link rel="shortcut icon" href="/favicon.ico" />

</head>
<body>

<h1><?php echo OpenPNEConfig::get('sns_name') ?>管理画面</h1>

<?php if ($sf_user->isAuthenticated()) : ?>
<ul>
<li><?php echo link_to('SNS設定', 'sns/config'); ?>
  <ul>
    <li><?php echo link_to('お知らせ設定', 'sns/informationConfig'); ?></li>
  </ul>
</li>
<li><?php echo link_to('ナビ設定', 'navi/index'); ?></li>
<li><?php echo link_to('プロフィール項目設定', 'profile/list'); ?></li>
</ul>
<?php endif; ?>

<?php echo $sf_content ?>

</body>
</html>
