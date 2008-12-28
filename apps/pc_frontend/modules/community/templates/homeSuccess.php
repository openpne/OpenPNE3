<?php slot('op_sidemenu'); ?>
<?php include_parts('memberImageBox', 'image', array('name' => $community->getName(), 'image' => $community->getImageFileName())) ?>

<?php
$option = array(
  'title' => __('コミュニティメンバー'),
  'list' => $community->getMembers(9),
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(sprintf('%s(%d)', __('全てを見る'), $community->countCommunityMembers()) => 'community/memberList?id=' . $community->getId()),
);
include_parts('nineTable', 'frendList', $option);
?>

<?php end_slot(); ?>

<?php
$list = array(
  'コミュニティ名' => $community->getName(),
  '開設日'         => $community->getCreatedAt(),
  '管理者'         => $community_admin->getName(),
  'メンバー数'     => $community->countCommunityMembers(),
  '説明文'         => nl2br($community->getConfig('description')),
);
include_list_box('communityHome', $list, array('title' => 'コミュニティ'))
?>

<ul>
<?php if ($isEditCommunity) : ?>
<li><?php echo link_to('このコミュニティを編集する', 'community/edit?id=' . $community->getId()) ?></li>
<?php endif; ?>

<?php if (!$isAdmin) : ?>
<?php if ($isCommunityMember) : ?>
<li><?php echo link_to('このコミュニティを退会する', 'community/quit?id=' . $community->getId()) ?></li>
<?php else : ?>
<li><?php echo link_to('このコミュニティに参加する', 'community/join?id=' . $community->getId()) ?></li>
<?php endif; ?>
<?php endif; ?>
</ul>
