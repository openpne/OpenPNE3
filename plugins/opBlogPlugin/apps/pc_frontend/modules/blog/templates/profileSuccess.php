<?php

include_parts(
  'BlogListPage',
  'blogProfile',
  array(
    'title' => sprintf(__('Newest blog of %s'), $member->getName()),
    'list' => $sf_data->getRaw('blogList'),
    'showName' => false
  )
);
