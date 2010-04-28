<?php

$list = array();
if (count($communities))
{
  foreach ($communities as $community)
  {
    $list[] = link_to($community->getName(), '@community_home?id='.$community->getId());
  }
}

$option = array(
  'title' => __('%Community% list with this member'),
  'border' => true,
  'moreInfo' => array(
    link_to(__('More'), '@community_joinlist?id='.$member->getId())
  ),
);
op_include_list('communityList', $list, $option);
