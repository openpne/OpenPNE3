<?php op_mobile_page_title($member->getName()) ?>
<?php $culture = sfCultureInfo::getInstance($sf_user->getCulture()); ?>

<?php if ($member == $sf_user->getMember()) : ?>
<font color="<?php echo $op_color["core_color_22"] ?>">
<?php echo __('This is your page other member see.') ?><br>
<?php echo __('If you edit profile, access %1%.', array('%1%' => link_to('「'. __('Edit profile') .'」', '@member_editProfile'))) ?>
</font>
<?php endif; ?>

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
<?php echo op_image_tag_sf_image($member->getImageFileName(), array('size' => '120x120', 'format' => 'jpg')) ?>
<?php if ($relation->isSelf()) : ?>
<br><?php echo link_to(__('Edit Photo'), 'member/configImage') ?>
<?php elseif ($member->getImageFileName()) : ?>
<br><?php echo link_to(__('Show Photo'), 'friend/showImage?id='.$member->getId()) ?>
<?php endif; ?>
</td>
<td valign="top">
<?php
$list = array();
if ($member->getAge(true))
{
  $ageValue = __('%1% years old', array('%1%' => $member->getAge()));
  if ($member->getConfig('age_public_flag') == ProfileTable::PUBLIC_FLAG_FRIEND)
  {
    $ageValue .= ' ('.__('Only Open to %my_friend%', array(
      '%my_friend%' => $op_term['my_friend']->titleize()->pluralize(),
    )).')';
  }

  $list[__('Age')] = $ageValue;
}

foreach ($member->getProfiles(true) as $profile)
{
  $caption = $profile->getCaption();
  $value = $profile;

  if ('' === (string)$profile)
  {
    continue;
  }

  if ($profile->getProfile()->isPreset())
  {
    $presetConfig = $profile->getProfile()->getPresetConfig();
    $caption = __($presetConfig['Caption']);
    if ('country_select' === $profile->getFormType())
    {
      $value = __($culture->getCountry((string)$profile));
    }
    elseif ('op_preset_birthday' === $profile->getName())
    {
      $value = op_format_date((string)$profile, 'XShortDateJa');
    }
    else
    {
      $value = __((string)$profile);
    }
  }

  if ('textarea' === $profile->getFormType())
  {
    $value = op_auto_link_text_for_mobile($value);
  }

  if ($member->getId() == $sf_user->getMemberId())
  {
    if ($profile->getPublicFlag() == ProfileTable::PUBLIC_FLAG_FRIEND)
    {
      $value .= '<font color="'.$op_color["core_color_22"].'">('.__('Only Open to %my_friend%').')</font><br>';
    }
    elseif ($profile->getPublicFlag() == ProfileTable::PUBLIC_FLAG_WEB && $profile->Profile->is_public_web)
    {
      $value .= '<font color="'.$op_color["core_color_22"].'">('.__('All Users on the Web').')</font><br>';
    }
  }

  $list[$caption] = $value;
}

?>

<?php foreach ($list as $caption => $value) : ?>
<font color="<?php echo $op_color["core_color_19"] ?>"><?php echo $caption ?>:</font><br>
<?php echo $value ?><br>
<?php endforeach; ?>
</td>
</tr>

<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
</td></tr>

<tr><td colspan="2">

<?php if (opConfig::get('enable_friend_link') && !$relation->isFriend() && !$relation->isSelf() && $relation->isAllowed($sf_user->getRawValue()->getMember(), 'friend_link')) : ?>
<?php echo link_to(__('Makes %friend%', array('%friend%' => $op_term['friend']->pluralize())), 'friend/link?id='.$member->getId()) ?><br>
<?php endif; ?>

<?php include_customizes('menu', 'friendTop') ?>
<?php include_component('default', 'nav', array('type' => 'mobile_friend', 'id' => $member->getId())) ?>
<?php include_customizes('menu', 'friendBottom') ?>

<?php include_customizes('menu', 'bottom') ?>
</td></tr>

<tr><td colspan="2" align="center">
<hr color="<?php echo $op_color["core_color_11"] ?>" size="3">
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

<?php if ($mobileBottomGadgets) : ?>
<?php foreach ($mobileBottomGadgets as $gadget) : ?>
<?php if ($gadget->isEnabled()) : ?>
<?php include_component($gadget->getComponentModule(), $gadget->getComponentAction(), array('gadget' => $gadget)); ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>

<?php slot('op_mobile_footer') ?>
<table width="100%">
<tbody><tr><td align="center" bgcolor="<?php echo $op_color["core_color_2"] ?>">
<font color="<?php echo $op_color["core_color_18"] ?>"><a href="<?php echo url_for('@homepage') ?>" accesskey="0"><font color="<?php echo $op_color["core_color_18"] ?>">0. <?php echo __('home') ?></font></a> / <a href="#top"><font color="<?php echo $op_color["core_color_18"] ?>"><?php echo __('top') ?></font></a> / <a href="#bottom" accesskey="8"><font color="<?php echo $op_color["core_color_18"] ?>">8. <?php echo __('bottom') ?></font></a></font><br>
</td></tr></tbody></table>
<?php end_slot(); ?>
