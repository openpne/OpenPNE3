<?php slot('op_sidemenu'); ?>
<?php include_parts('memberImageBox', 'image', array('name' => $member->getName(), 'image' => $member->getImageFileName())) ?>

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

<?php
$list = array();
foreach ($member->getProfiles() as $profile)
{
  $list[$profile->getCaption()] = $profile;
}
include_list_box('profile', $list, array('title' => 'プロフィール'))
?>
