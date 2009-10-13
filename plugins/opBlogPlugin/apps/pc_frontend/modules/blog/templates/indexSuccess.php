<?php

include_parts(
  'BlogListPage',
  'blogFriend',
  array(
    'title' => __('Newest blog'),
    'list' => $sf_data->getRaw('blogList'),
    'showName' => true
  )
);
