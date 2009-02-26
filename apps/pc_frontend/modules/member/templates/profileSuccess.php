<?php slot('op_sidemenu'); ?>
<?php use_helper('Date'); ?>
<?php
$moreInfo = array(
  '('.__('Last Login').':'.distance_of_time_in_words($member->getLastLoginTime()).')'
);


if ($relation->isSelf())
{
  $moreInfo[] = link_to(__('Edit Photo'), 'member/configImage');
}
else
{
  if ($member->getImageFileName())
  {
    $moreInfo[] = link_to(__("Show more Photos"), 'friend/showImage?id='.$member->getId());
  }
}


$options = array(
  'name'     => $member->getName(),
  'image'    => $member->getImageFileName(),
  'moreInfo' => $moreInfo,
);
op_include_parts('memberImageBox', 'memberImageBox', $options);
?>

<?php
$options = array(
  'title' => __('Friends List'),
  'list' => $member->getFriends(9),
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $member->countFriends()), 'friend/list?id='.$member->getId())),
);
op_include_parts('nineTable', 'frendList', $options);
?>

<?php
$options = array(
  'title' => __('Communities List'),
  'list' => $communities,
  'link_to' => 'community/home?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $member->countCommunityMembers()), 'community/joinlist')),
);
op_include_parts('nineTable', 'communityList', $options);
?>
<?php end_slot(); ?>

<?php slot('op_top'); ?>
<?php if ($relation->isSelf()): ?>
<?php ob_start() ?>
<p><?php echo __('Other members look your page like this.') ?></p>
<p><?php echo __('If you teach your page to other members, please use following URL.') ?><br />
<?php echo url_for('member/profile?id='.$member->getId(), true) ?></p>
<p><?php echo __('If you edit this page, please visit %1%.', array('%1%' => link_to(__('Edit profile'), 'member/editProfile'))) ?></p>
<?php $content = ob_get_clean() ?>
<?php op_include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', array('body' => $content)) ?>
<?php elseif (!$relation->isFriend()): ?>
<?php ob_start() ?>
<p><?php echo __('If %1% is your friend, let us add to friends it!', array('%1%' => $member->getName())) ?><br />
<?php echo link_to(__('Add friends'), 'friend/link?id='.$member->getId()) ?>
</p>
<?php $content = ob_get_clean() ?>
<?php op_include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', array('body' => $content)) ?>
<?php endif; ?>
<?php end_slot(); ?>

<?php
$list = array();
foreach ($member->getProfiles() as $profile)
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
?>
