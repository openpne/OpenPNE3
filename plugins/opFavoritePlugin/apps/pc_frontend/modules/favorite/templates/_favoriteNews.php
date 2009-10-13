<?php

if (count($diaryList))
{
  include_parts(
    'diaryListBox',
    'favoriteHomeDiary_'.$gadget->getId(),
    array(
      'title' => __('The favorite newest diary'),
      'list' => $diaryList,
      'showName' => true,
      'moreInfo' => 'favorite/diary',
      'link_to' => 'diary/%d'
    )
  );
}

if (count($blogList))
{
  include_parts(
    'blogListBox',
    'favoriteHomeBlog_'.$gadget->getId(),
    array(
      'title' => __('The favorite newest blog'),
      'list' => $blogList,
      'showName' => true,
      'moreInfo' => 'favorite/blog'
    )
  );
}
