<?php op_mobile_page_title($community->getName(), __('Manage member')) ?>

<center>
<?php op_include_pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $communityMember = Doctrine::getTable('communityMember')->retrieveByMemberIdAndCommunityId($member->getId(), $community->getId());
  $list_str = link_to($member->getName(), 'member/profile?id='.$member->getId());
  $operation = array();
  if ('admin' !== $communityMember->getPosition())
  {
    $operation[] = link_to(__('Drop this member'), 'community/dropMember?id='.$community->getId().'&member_id='.$member->getId());
  }

  if (!$communityMember->getPosition())
  {
    $operation[] = link_to(__("Take over this %community%'s administrator to this member"), 'community/changeAdminRequest?id='.$community->getId().'&member_id='.$member->getId());
  }
  elseif ('admin_confirm' === $communityMember->getPosition())
  {
    $operation[] = __("You are taking over this %community%'s administrator to this member now.");
  }

  $list[] = $list_str.(count($operation) ? '<br><br>'.implode('<br>', $operation) : '');
}
$option = array(
  'border' => true,
);
op_include_list('memberList', $list, $option);
?>

<?php op_include_pager_navigation($pager, 'community/memberManage?page=%d&id='.$id, array('is_total' => false)); ?>
