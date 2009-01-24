<?php
$options = array(
  'title' => __('Friends List'),
  'list' => $friends,
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $member->countFriends()), 'friend/list?id='.$member->getId())),
  'type' => $sf_data->getRaw('gadget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);

if ($member->getId() == $sf_user->getMember()->getId())
{
  $options['moreInfo'][] = link_to(__('My Friends Seetting'), 'friend/manage');
}

op_include_parts('nineTable', 'frendList', $options);
