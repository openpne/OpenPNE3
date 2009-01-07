<html>
<head>
<?php include_http_metas() ?>
<title><?php echo $op_config['sns_name'] ?></title>
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

<a name="#top"></a>

<?php echo $sf_content ?>

<a name="#bottom"></a>
<?php if(!include_slot('op_mobile_footer')): ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0.ﾎｰﾑ</font></a> / <a href="#top" accesskey="2"><font color="#eeeeee">2.上へ</font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8.下へ</font></a></font><br>
</td></tr></tbody></table>
<?php endif; ?>
</body>
</html>
