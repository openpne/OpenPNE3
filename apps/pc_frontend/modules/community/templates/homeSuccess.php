<?php slot('op_sidemenu'); ?>
<?php
$options = array(
  'name'   => $community->getName(),
  'image'  => $community->getImageFileName(),
);
op_include_parts('memberImageBox', 'communityImageBox', $options);
?>

<?php
$options = array(
  'title' => __('コミュニティメンバー'),
  'list' => $community->getMembers(9),
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('全てを見る'), $community->countCommunityMembers()), 'community/memberList?id='.$community->getId())),
);
if ($isAdmin)
{
  $options['moreInfo'][] = link_to(__('メンバー管理'), 'community/memberManage?id='.$community->getId());
}
op_include_parts('nineTable', 'frendList', $options);
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
$options = array(
  'title' => __('コミュニティ'),
  'list' => $list,
);
op_include_parts('listBox', 'communityHome', $options);
?>

<ul>
<?php if ($isEditCommunity): ?>
<li><?php echo link_to('このコミュニティを編集する', 'community/edit?id=' . $community->getId()) ?></li>
<?php endif; ?>

<?php if (!$isAdmin): ?>
<?php if ($isCommunityMember): ?>
<li><?php echo link_to('このコミュニティを退会する', 'community/quit?id=' . $community->getId()) ?></li>
<?php else : ?>
<li><?php echo link_to('このコミュニティに参加する', 'community/join?id=' . $community->getId()) ?></li>
<?php endif; ?>
<?php endif; ?>
</ul>
