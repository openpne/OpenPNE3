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

  if ('textarea' === $profile->getFormType())
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

  $list[$caption] = $profileValue;
}
?>
<div class="row">
  <div class="gadget_header span12"><?php echo __('Profile') ?></div>
</div>
<div class="row">
<table class="table-striped span12">
<tbody>
<tr><td><?php echo $op_term['nickname'] ?></td><td><?php echo $member->getName(); ?></td></tr>
<?php foreach ($list as $profileKey => $profileValue): ?>
<tr><td><?php echo __($profileKey); ?></td><td><?php echo $profileValue; ?></td></tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
