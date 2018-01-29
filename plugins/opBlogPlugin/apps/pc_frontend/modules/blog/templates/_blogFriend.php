<?php

if (count($blogRssCacheList))
{
  op_include_parts(
    'BlogListBox',
    'blogFriend_'.$gadget->getId(),
    array(
      'class' => 'homeRecentList',
      'title' => __('Friends newest blog'),
      'blogRssCacheList' => $blogRssCacheList,
      'showName' => true,
      'moreInfo' => array(link_to(__('More info'), '@blog_friend'))
    )
  );
}
