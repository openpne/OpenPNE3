<?php
$options = array(
  'title' => __('コミュニティリスト'),
  'list' => $communities,
  'link_to' => 'community/home?id=',
  'moreInfo' => array(sprintf('%s(%d)', __('全てを見る'), $member->countCommunityMembers()) => 'community/joinlist'),
  'type' => $sf_data->getRaw('gadget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);
op_include_parts('nineTable', 'communityList', $options);
