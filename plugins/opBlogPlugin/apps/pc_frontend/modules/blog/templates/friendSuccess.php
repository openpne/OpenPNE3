<?php

include_parts(
  'BlogListPage',
  'blogIndex',
  array(
    'title' => __('Newest blog'),
    'list' => $sf_data->getRaw('blogList'),
    'showName' => true
  )
);
