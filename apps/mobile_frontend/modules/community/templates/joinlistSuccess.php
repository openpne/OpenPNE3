<?php op_mobile_page_title($member->getName()) ?>

<center>
<?php op_include_pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $community) {
  $list[] = link_to(sprintf('%s(%d)', $community->getName(), $community->countCommunityMembers()), '@community_home?id='.$community->getId());
}
$option = array(
  'border' => true,
);
op_include_list('communityList', $list, $option);
?>

<?php op_include_pager_navigation($pager, '@community_joinlist?page=%d&id='.$member->getId(), array('is_total' => false)); ?>
