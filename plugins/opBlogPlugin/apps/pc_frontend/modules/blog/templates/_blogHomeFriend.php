<?php

if (count($blogList))
{
  include_parts(
    'BlogListBox',
    'blogHomeFriend_'.$gadget->getId(),
    array(
      'title' => __('Friends newest blog'),
      'list' => $blogList,
      'showName' => true,
      'moreInfo' => 'blog/friend'
    )
  );
}
