<?php

op_mobile_page_title(__('Ranking'), __('Access number No1 member'));

echo '<center>' . __('It is a ranking of the member with a lot of numbers of yesterday of accesses.') . '</center>';

$list = array();
for ($i = 0; $i < $member_list['number']; $i++)
{
  $member = $member_list['model'][$i];
  $list[] = sprintf(__('No%s'), $member_list['rank'][$i]) . '<br />'
          . link_to($member->getName(), 'member/profile?id=' . $member->getId()) . sprintf(__(' :%saccess'), $member_list['count'][$i]);
}

$options = array(
  'border' => true,
);

op_include_list('rankingList', $list, $options);
op_include_parts('rankingLink', 'RankingLink');
