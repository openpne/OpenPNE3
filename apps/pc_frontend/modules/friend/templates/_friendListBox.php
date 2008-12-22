<?php
$option = array(
  'title' => __('フレンドリスト'),
  'list' => $friends,
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(sprintf('%s(%d)', __('全てを見る'), $member->countFriends()) => 'friend/list?id='.$member->getId()),
  'type' => $sf_data->getRaw('widget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);
include_parts('nineTable', 'frendList', $option);
