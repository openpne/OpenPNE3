<?php if ($mobileTopGadgets) : ?>
<?php foreach ($mobileTopGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
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
<?php echo $sf_user->getMember()->getName() ?><br>
</td>

<td valign="top">
<?php include_component('default', 'navi', array('type' => 'mobile_home_side')) ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="#0d6ddf" size="3">
<?php echo link_to(__('Profile'), 'member/profile') ?>
<hr color="#0d6ddf" size="3">
</td></tr>

<tr><td colspan="2">
<?php include_component('default', 'navi', array('type' => 'mobile_home')) ?>
</td></tr>

<tr><td colspan="2" align="center">
<?php include_customizes('menu', 'bottom') ?>
</td></tr>

</table>

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
  link_to(__('Edit profile'), 'member/editProfile'),
);
op_include_parts('list', 'profileEdit', $list, array('title' => __('Edit profile')))
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
$list[] = link_to(__('Setting easy login'), 'member/configUID');
op_include_parts('list', 'configEdit', $list, array('title' => __('Settings')))
?>

<?php if ($mobileBottomGadgets) : ?>
<?php foreach ($mobileBottomGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<hr color="#0d6ddf">

■<?php echo link_to(__('Logout'), 'member/logout') ?><br>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="#0d6ddf">
<font color="#eeeeee"><a href="<?php echo url_for('member/home') ?>" accesskey="0"><font color="#eeeeee">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="#eeeeee">↑ <?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="#eeeeee">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
