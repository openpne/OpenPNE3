<?php op_mobile_page_title($community->getName(), __('Manage member')) ?>

<center>
<?php op_include_pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $communityMembers = $member->getCommunityMember();
  $list_str = link_to($member->getName(), 'member/profile?id='.$member->getId());
  if ('admin' !== $communityMembers[0]->getPosition())
  {
    $list_str .= '&nbsp;' . link_to(__('Drop this member'), 'community/dropMember?id='.$community->getId().'&member_id='.$member->getId());
  }
  $list[] = $list_str;
}
$option = array(
  'border' => true,
);
op_include_list('memberList', $list, $option);
?>

<?php op_include_pager_navigation($pager, 'community/memberList?page=%d&id='.$id, array('is_total' => false)); ?>
