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

<a name="#top">

<?php echo $sf_content ?>

<a name="#bottom">

<hr color="#0d6ddf">

■<?php echo link_to('ﾛｸﾞｱｳﾄ', 'member/logout') ?><br>

<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><font color="#eeeeee"><?php echo link_to('ﾎｰﾑ', 'member/home') ?></font> / <a href="#top"><font color="#eeeeee">↑上へ</font></a> / <a href="#bottom"><font color="#eeeeee">下へ</font></a></font><br>
</td></tr></tbody></table>

</body>
</html>
