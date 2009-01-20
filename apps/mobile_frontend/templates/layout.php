<html>
<head>
<?php include_http_metas() ?>
<?php if ($op_config['sns_title']) : ?>
<title><?php echo $op_config['sns_title'] ?></title>
<?php else: ?>
<title><?php echo $op_config['sns_name'] ?></title>
<?php endif; ?>
<?php if ($sf_request->getMobile()->isSoftBank() && $op_config['font_size']) : ?>
<style type="text/css">
*{font-size:small;}
</style>
<?php elseif ($sf_request->getMobile()->isEZWeb() && $op_config['font_size']) : ?>
<style type="text/css">
*{font-size:xx-small;}
</style>
<?php endif; ?>
</head>
<body>

<a name="#top"></a>

<?php if(!include_slot('op_mobile_header')): ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo $op_config['sns_name'] ?></a></font><br>
</td></tr>
</table>
<?php endif; ?>
<?php if ($sf_user->hasFlash('error')): ?>
<font color="#FF0000"><?php echo __($sf_user->getFlash('error')) ?></font>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<font color="#FF0000"><?php echo __($sf_user->getFlash('notice')) ?></font>
<?php endif; ?>

<?php echo $sf_content ?>

<a name="#bottom"></a>

<?php include_component('default', 'navi', array('type' => 'mobile_global')) ?>

<?php if(!include_slot('op_mobile_footer')): ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0.ﾎｰﾑ</font></a> / <a href="#top" accesskey="2"><font color="#eeeeee">2.上へ</font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8.下へ</font></a></font><br>
</td></tr></tbody></table>
<?php endif; ?>
</body>
</html>
