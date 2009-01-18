<?php include_page_title($op_config['sns_name']) ?>

<?php if ($mobileTopWidgets) : ?>
<?php foreach ($mobileTopWidgets as $widget) : ?>
<?php if ($widget->isEnabled()) : ?>
<?php include_component($widget->getComponentModule(), $widget->getComponentAction(), array('widget' => $widget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<table width="100%" bgcolor="#EEEEFF">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="#0D6DDF" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo image_tag_sf_image($sf_user->getMember()->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
<br>
<?php echo $sf_user->getMember()->getName() ?>さん<br>
</td>

<td valign="top">
<?php include_component('default', 'navi', array('type' => 'mobile_home_side')) ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="#0d6ddf" size="3">
<?php echo link_to('ﾌﾟﾛﾌｨｰﾙ', 'member/profile') ?>
<hr color="#0d6ddf" size="3">
</td></tr>

<tr><td colspan="2">
<?php include_component('default', 'navi', array('type' => 'mobile_home')) ?>
</td></tr>

<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'bottom') ?>
</td></tr>

</table>

<?php if ($mobileContentsWidgets) : ?>
<?php foreach ($mobileContentsWidgets as $widget) : ?>
<?php if ($widget->isEnabled()) : ?>
<?php include_component($widget->getComponentModule(), $widget->getComponentAction(), array('widget' => $widget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<br>

<?php
$list = array(
  link_to('ﾌﾟﾛﾌｨｰﾙ変更', 'member/editProfile'),
);
include_mobile_parts('list', 'profileEdit', $list, array('title' => 'ﾌﾟﾛﾌｨｰﾙ変更'))
?>

<?php
$list = array();
$attributes = sfConfig::get('openpne_member_category_attribute');
foreach (sfConfig::get('openpne_member_category') as $key => $value)
{
  $title = $key;
  if (!empty($attributes[$key]['caption']))
  {
    $title = $attributes[$key]['caption'];
  }

  if (count($value))
  {
    $list[] = link_to($title, 'member/config?category='.$key);
  }
}
$list[] = link_to('かんたんﾛｸﾞｲﾝ設定', 'member/configUID');
include_mobile_parts('list', 'configEdit', $list, array('title' => '設定変更'))
?>

<?php if ($mobileBottomWidgets) : ?>
<?php foreach ($mobileBottomWidgets as $widget) : ?>
<?php if ($widget->isEnabled()) : ?>
<?php include_component($widget->getComponentModule(), $widget->getComponentAction(), array('widget' => $widget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<hr color="#0d6ddf">

■<?php echo link_to('ﾛｸﾞｱｳﾄ', 'member/logout') ?><br>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0.ﾎｰﾑ</font></a> / <a href="#top"><font color="#eeeeee">↑上へ</font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8.下へ</font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
