<html>
<head>
<?php include_http_metas() ?>
<?php include_title() ?>
<?php if ($sf_request->getMobile()->isSoftBank() && $op_config['font_size']): ?>
<style type="text/css">
*{font-size:small;}
</style>
<?php elseif ($sf_request->getMobile()->isEZWeb() && $op_config['font_size']): ?>
<style type="text/css">
*{font-size:xx-small;}
</style>
<?php endif; ?>
<?php echo $op_config->get('mobile_html_head') ?>
</head>
<body>
<?php echo $op_config->get('mobile_header') ?>

<a name="top"></a>

<?php include_component('default', 'headerGadgets'); ?>

<?php if (!include_slot('op_mobile_header')): ?>
<table width="100%">
<tr><td align="center" bgcolor="<?php echo $op_color["core_color_2"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a name="top"><?php echo $op_config['sns_name'] ?></a></font><br>
</td></tr>
</table>
<?php endif; ?>
<?php if ($sf_user->hasFlash('error')): ?>
<font color="<?php echo $op_color["core_color_22"] ?>"><?php echo __($sf_user->getFlash('error')) ?></font><br>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<font color="<?php echo $op_color["core_color_22"] ?>"><?php echo __($sf_user->getFlash('notice')) ?></font><br>
<?php endif; ?>

<?php echo $sf_content ?>

<?php include_component('default', 'footerGadgets'); ?>

<?php if (has_slot('op_mobile_footer_menu')): ?>
<hr color="<?php echo $op_color["core_color_11"] ?>">
<?php include_slot('op_mobile_footer_menu'); ?>
<?php endif; ?>

<?php echo op_within_page_link(''); ?>
<a name="bottom"></a>

<?php include_component('default', 'nav', array('type' => 'mobile_global', 'line' => false)) ?>

<?php if (has_slot('op_mobile_footer')): ?>
<?php include_slot('op_mobile_footer') ?>
<?php else: ?>
<?php if ($sf_user->hasCredential('SNSMember')): ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="<?php echo $op_color["core_color_2"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a href="<?php echo url_for('@homepage') ?>" accesskey="0"><font color="<?php echo $op_color["core_color_18"] ?>">0.<?php echo __('home') ?></font></a> / <a href="#top" accesskey="2"><font color="<?php echo $op_color["core_color_18"] ?>">2. <?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="<?php echo $op_color["core_color_18"] ?>">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php endif; ?>
<?php endif; ?>
<?php echo $op_config->get('mobile_footer') ?>
</body>
</html>
