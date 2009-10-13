<?php
$list = array();
for ($i = 0; $i < $member_list['number']; $i++)
{
  $member = $member_list['model'][$i];
  $list[$i][sprintf(__('No%s'), $member_list['rank'][$i])] =
    link_to($member->getName(), 'member/profile?id=' . $member->getId()) . sprintf(__(' :%saccess'), $member_list['count'][$i]);
  if ($member->getProfile('self_intro'))
  {
    $list[$i][$member->getProfile('self_intro')->getCaption()] = nl2br($member->getProfile('self_intro'));
  }
}

$options = array(
  'title'          => sprintf(__('The No1 of the number of access member is %s'), $member_list['model'][0]->getName()),
  'link_to_detail' => 'member/profile?id=%d',
  'model'          => $member_list['model'],
  'list'           => $list,
  'rank'           => $member_list['rank'],
);

slot('op_sidemenu');
include_parts('rankingLink', 'RankingLink');
end_slot();
include_parts('rankingResultList', 'RankingResultList', $options);
