<?php

if (count($blogRssCacheList))
{
  $param = '';
  if ($member->getId() != $sf_user->getMemberId())
  {
    $param = '_profile?id='.$member->getId();
    $title = __('Newest blog');
  }
  else
  {
    $title = sprintf(__('My blog'));
  }

  op_include_parts(
    'BlogListBox',
    'blogUser_'.$gadget->getId(),
    array(
      'class' => 'homeRecentList',
      'title' => $title,
      'blogRssCacheList' => $blogRssCacheList,
      'showName' => false,
      'moreInfo' => array(link_to(__('More info'), '@blog_user'.$param))
    )
  );
}
