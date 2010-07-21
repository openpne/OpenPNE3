<?php

$culture = sfCultureInfo::getInstance($sf_user->getCulture());

$list = array();
foreach ($member->getProfiles(true) as $profile)
{
  $caption = $profile->getProfile()->getCaption();
  if ($profile->getProfile()->isPreset())
  {
    $presetConfig = $profile->getProfile()->getPresetConfig();
    $caption = __($presetConfig['Caption']);
  }

  $profileValue = (string)$profile;
  if ($profile->getFormType() === 'textarea')
  {
    $profileValue = op_auto_link_text(nl2br($profileValue));
  }

  if ($profile->getProfile()->isPreset())
  {
    if ($profile->getFormType() === 'country_select')
    {
      $profileValue = $culture->getCountry($profileValue);
    }

    $profileValue = __($profileValue);
  }

  if ($member->getId() == $sf_user->getMemberId() && $profile->getPublicFlag() == ProfileTable::PUBLIC_FLAG_FRIEND)
  {
    $profileValue .= ' ('.__('Only Open to %my_friend%', array(
      '%my_friend%' => $op_term['my_friend']->titleize()->pluralize(),
    )).')';
  }
  $list[$caption] = $profileValue;
}
$options = array(
  'title' => __('Profile'),
  'list' => $list,
);
op_include_parts('listBox', 'profile', $options);
