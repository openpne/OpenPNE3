<?php op_mobile_page_title($member->getName()) ?>

<center>
<?php echo pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $community) {
  $list[] = link_to(sprintf('%s(%d)', $community->getName(), $community->countCommunityMembers()), 'community/home?id='.$community->getId());
}
$option = array(
  'border' => true,
);
op_include_list('communityList', $list, $option);
?>

<?php echo pager_navigation($pager, 'community/joinlist?page=%d&id='.$member->getId(), false); ?>
