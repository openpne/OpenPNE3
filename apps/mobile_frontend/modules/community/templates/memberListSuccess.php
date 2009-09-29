<?php op_mobile_page_title($community->getName(), __('%Community% Members')) ?>

<center>
<?php op_include_pager_total($pager); ?>
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

<?php op_include_pager_navigation($pager, 'community/memberList?page=%d&id='.$id, array('is_total' => false)); ?>

<hr color="<?php echo $op_color['core_color_11'] ?>">

<?php echo link_to(__('Community Top'), 'community/home?id='.$community->getId()) ?>
