<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>

<title><?php echo OpenPNEConfig::get('sns_name') ?></title>

<link rel="shortcut icon" href="/favicon.ico" />

</head>
<body>

<h1><?php echo link_to(OpenPNEConfig::get('sns_name'), '@homepage') ?></h1>

<?php include_component('default', 'globalNavi') ?>

<?php if (isset($naviType)) : ?>
<?php include_component('default', 'localNavi', array('type' => $naviType)) ?>
<?php else: ?>
<?php include_component('default', 'localNavi') ?>
<?php endif; ?>

<?php echo $sf_content ?>

</body>
</html>
