<?php

op_include_parts(
  'BlogListPage',
  'blogUser',
  array(
    'class' => 'recentList',
    'title' => sprintf(__('Newest blog of %s'), $member->getName()),
    'blogRssCacheList' => $blogRssCacheList,
    'showName' => false
  )
);
