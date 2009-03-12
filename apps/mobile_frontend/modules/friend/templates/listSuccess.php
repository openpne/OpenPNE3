<?php op_mobile_page_title(__('Friend list')) ?>

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
op_include_list('friendList', $list, $option);
?>

<?php echo pager_navigation($pager, 'friend/list?page=%d&id='.$id , false); ?>

<?php echo link_to(__('Manage friend'), 'friend/manage') ?>
