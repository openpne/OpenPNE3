<?php use_helper('Javascript') ?>
<?php
op_include_parts('descriptionBox', 'smtProfileTop', array());
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
    elseif ('op_preset_birthday' === $profile->getName())
    {
      $profileValue = op_format_date($profileValue, 'XShortDateJa');
    }

    $profileValue = __($profileValue);
  }

  $list[$caption] = $profileValue;
}
?>
<div class="row">
  <div class="gadget_header span12"><?php echo __('Profile') ?></div>
</div>
<div class="row">
<table class="zebra-striped">
<tbody>
<tr><td><?php echo $op_term['nickname'] ?></td><td><?php echo $member->getName(); ?></td></tr>
<?php foreach ($list as $k => $v): ?>
<tr><td><?php echo __($k); ?></td><td><?php echo $v; ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
