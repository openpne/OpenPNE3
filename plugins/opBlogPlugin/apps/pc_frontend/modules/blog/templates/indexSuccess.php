<?php

op_include_parts(
  'BlogListPage',
  'blogFriend',
  array(
    'class' => 'recentList',
    'title' => __('Newest blog'),
    'blogRssCacheList' => $blogRssCacheList,
    'showName' => true
  )
);
