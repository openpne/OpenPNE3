<?php use_helper('Javascript') ?>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js"></script>
<script id="friendListTemplate" type="text/x-jquery-tmpl">
  <div class="span3">
    <div class="row_memberimg row"><a href="${member.profile_url}"><img src="${member.image}" class="rad10" width="57" height="57"></a></div>
    <div class="row_membername font10 row"><a href="${member.profile_url}">${member.name}</a> (${member.count})</div>
  </div>
</script>
<script type="text/javascript">
$(function(){
  $.getJSON( '<?php echo app_url_for('api', 'member/friendList.json', array('id' => $member->getId())); ?>&apiKey=' + openpne.apiKey, function(json) {
    $('#friendListTemplate').tmpl(json.data).appendTo('#memberFriendList');
  });
});
</script>
<?php if ($relation->isSelf()): ?>
<?php ob_start() ?>
<div class="alert-message block-message info">
<p><?php echo __('Other members look your page like this.') ?></p>
<p><?php echo __('If you teach your page to other members, please use following URL.') ?><br />
<?php echo url_for('@member_profile?id='.$member->getId(), true) ?></p>
<p><?php echo __('If you edit this page, please visit %1%.', array('%1%' => link_to(__('Edit profile'), '@member_editProfile'))) ?></p>
</div>
<?php $content = ob_get_clean() ?>
<?php op_include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', array('body' => $content)) ?>
<?php else: ?>
<?php if (!$relation->isFriend() && opConfig::get('enable_friend_link') && $relation->isAllowed($sf_user->getRawValue()->getMember(), 'friend_link')): ?>
<?php ob_start() ?>
<div class="alert-message block-message warning">
<p><?php echo __('If %1% is your friend, let us add to %my_friend% it!', array('%1%' => $member->getName(), '%my_friend%' => $op_term['my_friend']->pluralize())) ?><br />
<?php echo link_to(__('Add %my_friend%', array('%my_friend%' => $op_term['my_friend']->pluralize())), 'friend/link?id='.$member->getId()) ?>
</p>
</div>
<?php $content = ob_get_clean() ?>
<?php op_include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', array('body' => $content)) ?>
<?php endif; ?>
<?php endif; ?>

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

<div class="row">
  <div class="gadget_header span12"><?php echo __('Photo') ?></div>
</div>
<hr class="toumei" />
<div class="row">
  <div class="span12">
    <hr class="toumei" />
    <?php echo op_image_tag_sf_image($member->getImageFileName(), array('size' => '320x320')) ?>
  </div>
</div>
<hr class="toumei" />
<div class="row">
  <div class="gadget_header span12"><?php echo __('%friend% List', array('%friend%' => $op_term['friend'])) ?></div>
</div>
<hr class="toumei" />
<div class="row" id="memberFriendList">
</div>
 
