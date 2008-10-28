<?php include_page_title($member->getName()) ?>
<?php use_helper('Pagination'); ?>

<center>
<?php echo pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $community) {
  $list[] = link_to(sprintf('%s(%d)', $community->getName(), $community->countCommunityMembers()), 'community/home?id='.$community->getId());
}
$options = array(
  'border' => true,
);
include_list_box('communityList', $list, $options);
?>

<?php echo pager_navigation($pager, 'community/joinlist?page=%d&member_id=' . $sf_params->get('member_id'), false); ?>
