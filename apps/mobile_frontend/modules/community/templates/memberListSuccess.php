<?php op_mobile_page_title($community->getName(), __('Community Members')) ?>

<center>
<?php echo pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $list[] = link_to(sprintf('%s(%d)', $member->getName(), $member->countFriends()), 'member/profile?id='.$member->getId());
}
$option = array(
  'border' => true,
);
op_include_list('memberList', $list, $option);
?>

<?php echo pager_navigation($pager, 'community/memberList?page=%d&id='.$id, false); ?>
