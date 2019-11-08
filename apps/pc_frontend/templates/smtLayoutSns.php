<?php use_helper('Javascript') ?>
<?php use_helper('opAsset') ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php op_smt_use_stylesheet('bootstrap') ?>
<?php op_smt_use_stylesheet('smt_main') ?>
<?php op_smt_include_stylesheets() ?>
<meta name="viewport" content="width=320,user-scalable=no" />
<?php if (opConfig::get('enable_jsonapi') && opToolkit::isSecurePage()): ?>
<?php
$jsonData = array(
  'apiKey' => opToolkit::isSecurePage() ? $sf_user->getMemberApiKey() : '',
  'apiBase' => app_url_for('api', 'homepage'),
  'baseUrl' => $sf_request->getRelativeUrlRoot().'/',
);

$json = defined('JSON_PRETTY_PRINT') ? json_encode($jsonData, JSON_PRETTY_PRINT) : json_encode($jsonData);
?>
<script type="text/javascript">
var op = {
  api: <?php echo $json ?>
};
</script>
<?php endif ?>
<?php
op_smt_use_javascript('jquery.min.js');
op_smt_use_javascript('underscore-min.js');
op_smt_use_javascript('backbone-min.js');
op_smt_use_javascript('jquery.tmpl.min.js');
op_smt_use_javascript('jquery.notify.js');
op_smt_use_javascript('openpne/api.js');
op_smt_use_javascript('smt_main');
op_smt_use_javascript('smt_tosaka');
op_smt_use_javascript('smt_menu');
op_smt_include_javascripts();
?>
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
