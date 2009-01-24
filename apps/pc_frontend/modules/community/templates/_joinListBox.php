<?php
$options = array(
  'title' => __('Communities List'),
  'list' => $communities,
  'link_to' => 'community/home?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $member->countCommunityMembers()), 'community/joinlist')),
  'type' => $sf_data->getRaw('gadget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);
op_include_parts('nineTable', 'communityList', $options);
