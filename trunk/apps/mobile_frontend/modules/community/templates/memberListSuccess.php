<?php include_page_title($community->getName(), 'ﾒﾝﾊﾞｰﾘｽﾄ') ?>

<center>
<?php echo pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $list[] = link_to(sprintf('%s(%d)', $member->getName(), $member->countFriends()), 'member/profile?id='.$member->getId());
}
$options = array(
  'border' => true,
);
include_list_box('memberList', $list, $options);
?>

<?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id'), false); ?>
