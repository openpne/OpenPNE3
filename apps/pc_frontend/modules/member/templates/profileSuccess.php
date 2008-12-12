<?php slot('op_sidemenu'); ?>
<?php include_parts('memberImageBox', 'image', array('name' => $member->getName(), 'image' => $member->getImage())) ?>

<ul>
<li><?php echo link_to(sprintf('フレンド一覧(%d)', $member->countFriends()), 'friend/list?id=' . $member->getId()) ?></li>
<li><?php echo link_to(sprintf('参加コミュニティ一覧(%d)', $member->countCommunityMembers()), 'community/joinlist?member_id=' . $member->getId()) ?></li>
</ul>
<?php end_slot(); ?>

<?php
$list = array();
foreach ($member->getProfiles() as $profile)
{
  $list[$profile->getCaption()] = $profile;
}
include_list_box('profile', $list, array('title' => 'プロフィール'))
?>
