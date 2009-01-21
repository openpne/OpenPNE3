<?php
$options = array(
  'title' => __('フレンドリスト'),
  'list' => $friends,
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('全てを見る'), $member->countFriends()), 'friend/list?id='.$member->getId())),
  'type' => $sf_data->getRaw('gadget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);

if ($member->getId() == $sf_user->getMember()->getId())
{
  $options['moreInfo'][] = link_to(__('フレンド管理'), 'friend/manage');
}

op_include_parts('nineTable', 'frendList', $options);
