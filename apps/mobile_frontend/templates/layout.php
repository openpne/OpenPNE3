<html>
<head>
<?php include_http_metas() ?>
<title><?php echo OpenPNEConfig::get('sns_name') ?></title>
<?php if ($sf_request->getMobile()->isSoftBank()) : ?>
<style type="text/css">
*{font-size:small;}
</style>
<?php elseif ($sf_request->getMobile()->isEZWeb()) : ?>
<style type="text/css">
*{font-size:xx-small;}
</style>
<?php endif; ?>
</head>
<body>
<?php echo $sf_content ?>
</body>
</html>
