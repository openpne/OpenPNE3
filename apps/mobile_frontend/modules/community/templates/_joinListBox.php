<?php

$list = array();

if (count($communities))
{
  foreach ($communities as $community)
  {
    $list[] = link_to($community->getName(), 'community/home?id='.$community->getId());
  }
}

$option = array(
  'title' => __('%Community% list with this member'),
  'border' => true,
  'moreInfo' => array(
    link_to(__('More'), 'community/joinlist?id='.$member->getId())
  ),
);
op_include_list('communityList', $list, $option);
