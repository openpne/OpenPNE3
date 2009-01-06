<?php slot('op_sidemenu'); ?>
<?php use_helper('Date'); ?>
<?php
$moreInfo = array(
  '('.__('最終ログイン').':'.distance_of_time_in_words($member->getLastLoginTime()).')'
);
if ($relation->isSelf())
{
  $moreInfo[] = link_to(__('写真を編集'), 'member/configImage');
}

include_parts('memberImageBox', 'image', array(
  'name'     => $member->getName(),
  'image'    => $member->getImageFileName(),
  'moreInfo' => $moreInfo,
)) ?>

<?php
$option = array(
  'title' => __('フレンドリスト'),
  'list' => $member->getFriends(9),
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(sprintf('%s(%d)', __('全てを見る'), $member->countFriends()) => 'friend/list?id='.$member->getId()),
);
include_parts('nineTable', 'frendList', $option);
?>

<?php
$option = array(
  'title' => __('コミュニティリスト'),
  'list' => $communities,
  'link_to' => 'community/home?id=',
  'moreInfo' => array(sprintf('%s(%d)', __('全てを見る'), $member->countCommunityMembers()) => 'community/joinlist'),
);
include_parts('nineTable', 'communityList', $option);
?>
<?php end_slot(); ?>

<?php slot('op_top'); ?>
<?php if ($relation->isSelf()): ?>
<?php
$option = array(
  'body' => '
<p>※他のメンバーから見たあなたのページはこのようになります。</p>
<p>他のメンバーにあなたのページを教える場合は、以下のURLを使ってください。<br />
'.url_for('member/profile?id='.$member->getId(), true).'</p>
<p>プロフィールを変更する場合は「'.link_to(__('プロフィール変更'), 'member/editProfile').'」よりおこなってください。</p>
');
include_parts('descriptionBox', 'informationAboutThisIsYourProfilePage', $option);
?>
<?php endif; ?>
<?php end_slot(); ?>

<?php
$list = array();
foreach ($member->getProfiles() as $profile)
{
  $list[$profile->getCaption()] = $profile;
}
include_list_box('profile', $list, array('title' => 'プロフィール'))
?>
