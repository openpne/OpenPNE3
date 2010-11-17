<?php
$options = array(
  'title' => __('%community% List', array('%community%' => $op_term['community']->titleize())),
  'list' => $communities,
  'crownIds' => $sf_data->getRaw('crownIds'),
  'link_to' => '@community_home?id=',
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $member->countJoinCommunity()), '@community_joinlist?id='.$member->id)),
  'type' => $sf_data->getRaw('gadget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);
op_include_parts('nineTable', 'communityList_'.$gadget->getId(), $options);
