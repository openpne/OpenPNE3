<?php

$culture = sfCultureInfo::getInstance($sf_user->getCulture());

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
  $caption = $profile->getProfile()->getCaption();
  if ($profile->getProfile()->isPreset())
  {
    $presetConfig = $profile->getProfile()->getPresetConfig();
    $caption = __($presetConfig['Caption']);
  }

  $profileValue = (string)$profile;
  if ('' === $profileValue)
  {
    continue;
  }

  if ('textarea' == $profile->getFormType())
  {
    $profileValue = op_auto_link_text(nl2br($profileValue));
  }

  if ($profile->getProfile()->isPreset())
  {
    if ('country_select' === $profile->getFormType())
    {
      $profileValue = $culture->getCountry($profileValue);
    }
    elseif ('op_preset_birthday' === $profile->getName())
    {
      $profileValue = op_format_date($profileValue, 'XShortDateJa');
    }

    $profileValue = __($profileValue);
  }

  if ($member->getId() == $sf_user->getMemberId())
  {
    if ($profile->getPublicFlag() == ProfileTable::PUBLIC_FLAG_FRIEND)
    {
      $profileValue .= ' ('.__('Only Open to %my_friend%', array(
        '%my_friend%' => $op_term['my_friend']->titleize()->pluralize(),
      )).')';
    }
    elseif ($profile->getPublicFlag() == ProfileTable::PUBLIC_FLAG_WEB && $profile->Profile->is_public_web)
    {
      $profileValue .= ' ('.__('All Users on the Web').')';
    }
  }
  $list[$caption] = $profileValue;
}
$options = array(
  'title' => __('Profile'),
  'list' => $list,
);
op_include_parts('listBox', 'profile', $options);
