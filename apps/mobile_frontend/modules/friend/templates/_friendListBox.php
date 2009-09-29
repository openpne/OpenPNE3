<?php

$list = array();
foreach ($friends as $friendMember)
{
  $list[] = link_to(sprintf('%s(%d)', $friendMember->getName(), $friendMember->countFriends()), 'member/profile?id='.$friendMember->getId());
}
$option = array(
  'title' => __('%Friend% list'),
  'border' => true,
  'moreInfo' => array(
    link_to(__('More'), 'friend/list?id='.$member->getId())
  ),
);
op_include_list('friendList', $list, $option);
