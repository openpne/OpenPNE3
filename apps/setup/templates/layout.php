<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>

<?php include_http_metas() ?>
<?php include_metas() ?>

<title><?php echo __('Setup SNS') ?></title>


    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
</head>
<body id="<?php echo $sf_request->getParameter('module').'_'.$sf_request->getParameter('action') ?>"<?php if (!$sf_user->isAuthenticated()) : ?> class="insecure"<?php endif; ?>>
<div id="wrap">
<div id="contents" class="clearfix">

<div id="header">
<h1><?php echo __('Setup SNS'); ?></h1>
</div>


<div id="body">

<?php if (has_slot('title')): ?>
<h2><?php include_slot('title') ?></h2>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
<p id="flashError" class="flash"><?php echo __($sf_user->getFlash('error')) ?></p>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<p id="flashNotice" class="flash"><?php echo __($sf_user->getFlash('notice')) ?></p>
<?php endif; ?>


<?php echo $sf_content ?>
</div>
</div>

</div>


</body>
</html>
