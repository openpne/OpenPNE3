<?php
$list = array(
  'コミュニティ名' => $community->getName(),
  '開設日' => $community->getCreatedAt(),
  '管理者' => $community_admin->getName(),
  'メンバー数' => $community->countCommunityMembers(),
);
include_list_box('communityHome', $list, array('title' => 'コミュニティ'))
?>

<ul>
<li><?php echo link_to(sprintf('コミュニティメンバー一覧(%d)', $community->countCommunityMembers()), 'community/memberList?id=' . $community->getId()) ?></li>

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
