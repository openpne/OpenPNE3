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
  'title' => __('Community Members'),
  'list' => $community->getMembers(9),
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $community->countCommunityMembers()), 'community/memberList?id='.$community->getId())),
);
if ($isAdmin)
{
  $options['moreInfo'][] = link_to(__('Management member'), 'community/memberManage?id='.$community->getId());
}
op_include_parts('nineTable', 'frendList', $options);
?>
<?php end_slot(); ?>

<?php
$list = array(
  __('Community Name')   => $community->getName(),
  __('Date Created')         => op_format_date($community->getCreatedAt(), 'D'),
  __('Administrator')    => $community_admin->getName(),
  __('Count of Members') => $community->countCommunityMembers(),
  __('Description')      => op_auto_link_text(nl2br($community->getConfig('description'))),
);
$options = array(
  'title' => __('Community'),
  'list' => $list,
);
op_include_parts('listBox', 'communityHome', $options);
?>

<ul>
<?php if ($isEditCommunity): ?>
<li><?php echo link_to(__('Edit this community'), 'community/edit?id=' . $community->getId()) ?></li>
<?php endif; ?>

<?php if (!$isAdmin): ?>
<?php if ($isCommunityMember): ?>
<li><?php echo link_to(__('Leave this community'), 'community/quit?id=' . $community->getId()) ?></li>
<?php else : ?>
<li><?php echo link_to(__('Join this community'), 'community/join?id=' . $community->getId()) ?></li>
<?php endif; ?>
<?php endif; ?>
</ul>
