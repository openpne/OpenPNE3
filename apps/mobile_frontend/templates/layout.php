<html>
<head>
<?php include_http_metas() ?>
<?php if ($op_config['sns_title']): ?>
<title><?php echo $op_config['sns_title'] ?></title>
<?php else: ?>
<title><?php echo $op_config['sns_name'] ?></title>
<?php endif; ?>
<?php if ($sf_request->getMobile()->isSoftBank() && $op_config['font_size']): ?>
<style type="text/css">
*{font-size:small;}
</style>
<?php elseif ($sf_request->getMobile()->isEZWeb() && $op_config['font_size']): ?>
<style type="text/css">
*{font-size:xx-small;}
</style>
<?php endif; ?>
</head>
<body>

<a name="#top"></a>

<?php include_component('default', 'headerGadgets'); ?>

<?php if (!include_slot('op_mobile_header')): ?>
<table width="100%">
<tr><td align="center" bgcolor="#0D6DDF">
<font color="#EEEEEE"><a name="top"><?php echo $op_config['sns_name'] ?></a></font><br>
</td></tr>
</table>
<?php endif; ?>
<?php if ($sf_user->hasFlash('error')): ?>
<font color="#FF0000"><?php echo __($sf_user->getFlash('error')) ?></font><br>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<font color="#FF0000"><?php echo __($sf_user->getFlash('notice')) ?></font><br>
<?php endif; ?>

<?php echo $sf_content ?>

<?php include_component('default', 'footerGadgets'); ?>

<?php if (has_slot('op_mobile_footer_menu')): ?>
<hr color="#0d6ddf">
<?php include_slot('op_mobile_footer_menu'); ?>
<?php endif; ?>

<a name="#bottom"></a>

<?php include_component('default', 'nav', array('type' => 'mobile_global')) ?>

<?php if (!include_slot('op_mobile_footer')): ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0.<?php echo __('home') ?></font></a> / <a href="#top" accesskey="2"><font color="#eeeeee">2. <?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php endif; ?>
</body>
</html>
