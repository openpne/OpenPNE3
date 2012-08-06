<?php if ($mobileTopGadgets) : ?>
<?php foreach ($mobileTopGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<table width="100%" bgcolor="<?php echo $op_color["core_color_4"] ?>">
<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'top') ?>
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td align="center" width="50%" valign="top">
<?php echo op_image_tag_sf_image($sf_user->getMember()->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
<br>
<?php echo $sf_user->getMember()->getName() ?><br>
</td>

<td valign="top">
<?php include_customizes('menu', 'sideTop') ?>
<?php include_component('default', 'nav', array('type' => 'mobile_home_side', 'line' => false)) ?>
<?php include_customizes('menu', 'sideBottom') ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
<?php include_customizes('menu', 'centerTop') ?>
<?php include_component('default', 'nav', array('type' => 'mobile_home_center', 'separator' => ' / ')) ?>
<?php include_customizes('menu', 'centerBottom') ?>
</td></tr>

<tr><td colspan="2">
<?php include_customizes('menu', 'homeTop') ?>
<?php include_component('default', 'nav', array('type' => 'mobile_home')) ?>
<?php include_customizes('menu', 'homeBottom') ?>
</td></tr>

<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'bottom') ?>
</td></tr>

</table>
<br>
<?php if ($mobileContentsGadgets) : ?>
<?php foreach ($mobileContentsGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<br>

<?php
$list = array(
  link_to(__('Search Members'), '@member_search'),
  link_to(__('Search %Community%', array('%Community%' => $op_term['community']->pluralize())), 'community/search'),
);
op_include_list('search', $list, array('title' => __('Search')))
?>

<?php
$list = array(
  link_to(__('Edit profile'), '@member_editProfile'),
);
op_include_list('profileEdit', $list, array('title' => __('Edit profile')))
?>

<?php
$list = array();
foreach ($categories as $key => $value)
{
  if (count($value))
  {
    $list[] = link_to(__($categoryCaptions[$key]), 'member/config?category='.$key);
  }
}
$list[] = link_to(__('Setting easy login'), 'member/configUID');
$list[] = link_to(__('Delete your %1% account', array('%1%' => $op_config['sns_name'])), 'member/delete');
op_include_list('configEdit', $list, array('title' => __('Settings')));
?>

<?php if ($mobileBottomGadgets) : ?>
<?php foreach ($mobileBottomGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<?php slot('op_mobile_footer_menu') ?>
■<?php echo link_to(__('Logout'), 'member/logout') ?><br>
<?php end_slot(); ?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="<?php echo $op_color["core_color_2"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a href="<?php echo url_for('@homepage') ?>" accesskey="0"><font color="<?php echo $op_color["core_color_18"] ?>">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="<?php echo $op_color["core_color_18"] ?>"><?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="<?php echo $op_color["core_color_18"] ?>">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
