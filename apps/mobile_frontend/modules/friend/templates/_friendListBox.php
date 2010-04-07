<?php

$list = array();
foreach ($friends as $friendMember)
{
  $list[] = op_link_to_member($friendMember->getId(), '@obj_member_profile', array('link_target' => sprintf('%s(%d)', $friendMember->getName(), $friendMember->countFriends())));
}
$option = array(
  'title' => __('%Friend% list'),
  'border' => true,
  'moreInfo' => array(
    link_to(__('More'), 'friend/list?id='.$member->getId())
  ),
);
op_include_list('friendList', $list, $option);
