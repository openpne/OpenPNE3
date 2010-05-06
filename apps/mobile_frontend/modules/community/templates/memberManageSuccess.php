<?php op_mobile_page_title($community->getName(), __('Manage member')) ?>

<center>
<?php op_include_pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $communityMember = Doctrine::getTable('communityMember')->retrieveByMemberIdAndCommunityId($member->getId(), $community->getId());
  $list_str = op_link_to_member($member);
  $operation = array();
  if (!($communityMember->hasPosition(array('admin', 'sub_admin')) || $communityMember->getMemberId() === $sf_user->getMemberId()))
  {
    $operation[] = link_to(__('Drop this member'), 'community/dropMember?id='.$community->getId().'&member_id='.$member->getId());
  }

  if ($isAdmin)
  {
    if (!$communityMember->hasPosition(array('admin', 'admin_confirm', 'sub_admin')))
    {
      if ($communityMember->hasPosition('sub_admin_confirm'))
      {
        $operation[] = __("You are requesting this %community%'s sub-administrator to this member now.");
      }
      else
      {
        $operation[] = link_to(__("Request this %community%'s sub-administrator to this member"), 'community/subAdminRequest?id='.$community->getId().'&member_id='.$member->getId());
      }
    }
    elseif ($communityMember->hasPosition('sub_admin'))
    {
      $operation[] = link_to(__("Demotion from this %community%'s sub-administrator"), 'community/removeSubAdmin?id='.$community->getId().'&member_id='.$member->getId());
    }

    if (!$communityMember->hasPosition('admin'))
    {
      $operation[] = link_to(__("Take over this %community%'s administrator to this member"), 'community/changeAdminRequest?id='.$community->getId().'&member_id='.$member->getId());
    }
    elseif ($communityMember->hasPosition('admin_confirm'))
    {
      $operation[] = __("You are taking over this %community%'s administrator to this member now.");
    }
  }

  $list[] = $list_str.(count($operation) ? '<br><br>'.implode('<br>', $operation) : '');
}
$option = array(
  'border' => true,
);
op_include_list('memberList', $list, $option);
?>

<?php op_include_pager_navigation($pager, '@community_memberManage?page=%d&id='.$id, array('is_total' => false)); ?>
