<?php

op_mobile_page_title(__('Ranking'), __('Member of number No1 of friends'));

echo '<center>' . __('It is a ranking of the member with a lot of numbers of registered friends.') . '</center>';

$list = array();
for ($i = 0; $i < $member_list['number']; $i++)
{
  $member = $member_list['model'][$i];
  $list[] = sprintf(__('No%s'), $member_list['rank'][$i]) . '<br />'
          . link_to($member->getName(), 'member/profile?id=' . $member->getId()) . sprintf(__(' :%smember'), $member_list['count'][$i]);
}

$options = array(
  'border' => true,
);

op_include_list('rankingList', $list, $options);
op_include_parts('rankingLink', 'RankingLink');
