<?php
$option = array(
  'title' => __('コミュニティリスト'),
  'list' => $communities,
  'link_to' => 'community/home?id=',
  'moreInfo' => array(sprintf('%s(%d)', __('全てを見る'), $member->countCommunityMembers()) => 'community/joinlist'),
  'type' => $sf_data->getRaw('widget')->getConfig('type'),
  'col' => $sf_data->getRaw('widget')->getConfig('col'),
  'row' => $sf_data->getRaw('widget')->getConfig('row'),
);
include_parts('nineTable', 'communityList', $option);
