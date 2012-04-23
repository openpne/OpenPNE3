<?php use_helper('Javascript') ?>
<?php $sf_response->removeStylesheet('/opSkinBasicPlugin/css/main.css') ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php use_stylesheet('bootstrap') ?>
<?php use_stylesheet('smt_main') ?>
<?php include_stylesheets() ?>
<?php if (Doctrine::getTable('SnsConfig')->get('customizing_css')): ?>
<link rel="stylesheet" type="text/css" href="<?php echo url_for('@customizing_css') ?>" />
<?php endif ?>
<meta name="viewport" content="width=320px,user-scalable=no" />
<?php if (opConfig::get('enable_jsonapi') && opToolkit::isSecurePage()): ?>
<?php
$jsonData = array(
  'apiKey' => opToolkit::isSecurePage() ? $sf_user->getMemberApiKey() : '',
  'apiBase' => app_url_for('api', 'homepage'),
);

$json = defined('JSON_PRETTY_PRINT') ? json_encode($jsonData, JSON_PRETTY_PRINT) : json_encode($jsonData);

echo javascript_tag('
var openpne = '.$json.';
');
?>
<?php endif ?>
<?php use_javascript('jquery.min.js') ?>
<?php use_javascript('jquery.tmpl.min.js') ?>
<?php use_javascript('smt_main') ?>
<?php use_javascript('smt_notify') ?>
<?php use_javascript('smt_tosaka') ?>
<?php use_javascript('smt_menu') ?>
<?php include_javascripts() ?>
</head>
<body id="<?php printf('page_%s_%s', $this->getModuleName(), $this->getActionName()) ?>" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">

<?php include_partial('global/tosaka') ?>

<?php if ($sf_user->hasFlash('error')): ?>
<div id="global-error" class="row">
  <div class="alert alert-error">
    <?php echo __($sf_user->getFlash('error')); ?>
  </div>
</div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('notice')): ?>
<div id="global-error" class="row">
  <div class="alert alert-info">
    <?php echo __($sf_user->getFlash('notice')); ?>
  </div>
</div>
<?php endif; ?>

<?php echo $sf_content ?>

<div id="smartphoneFooter">
<?php include_component('default', 'smartphoneFooterGadgets') ?>
</div>

</body>
</html>
