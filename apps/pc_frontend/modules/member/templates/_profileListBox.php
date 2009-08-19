<?php

$list = array();
foreach ($member->getProfiles(true) as $profile)
{
  $caption = $profile->getCaption();
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
  if ($member->getId() == $sf_user->getMemberId() && $profile->getPublicFlag() == ProfileTable::PUBLIC_FLAG_FRIEND)
  {
    $profileValue .= ' ('.__('Only Open to My Friends').')';
  }
  $list[$caption] = $profileValue;
}
$options = array(
  'title' => __('Profile'),
  'list' => $list,
);
op_include_parts('listBox', 'profile', $options);
