<?php
$option = array(
  'title' => __('フレンドリスト'),
  'list' => $friends,
  'link_to' => 'member/profile?id=',
  'moreInfo' => array(sprintf('%s(%d)', __('全てを見る'), $member->countFriends()) => 'friend/list?id='.$member->getId()),
  'type' => $sf_data->getRaw('widget')->getConfig('type'),
  'col' => $sf_data->getRaw('widget')->getConfig('col'),
  'row' => $sf_data->getRaw('widget')->getConfig('row'),
);
include_parts('nineTable', 'frendList', $option);
