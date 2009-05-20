<?

$list = array();
foreach ($member->getProfiles(true) as $profile)
{
  $caption = $profile->getCaption();
  if ($profile->getFormType() === 'textarea')
  {
    $profile = op_auto_link_text(nl2br($profile));
  }
  $list[$caption] = $profile;
}
$options = array(
  'title' => __('Profile'),
  'list' => $list,
);
op_include_parts('listBox', 'profile', $options);
