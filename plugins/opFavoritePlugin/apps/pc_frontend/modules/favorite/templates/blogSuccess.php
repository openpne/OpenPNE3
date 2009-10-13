<?php

include_parts(
  'BlogListPage',
  'blogFavorite',
  array(
    'title' => __('The favorite newest blog'),
    'list' => $blogList,
    'showName' => true
  )
);
