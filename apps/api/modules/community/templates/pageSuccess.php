<?php

$data = array();

foreach ($pager as $community)
{
  $data[] = op_api_community($community);
}

return array(
  'status' => 'success',
  'data' => $data,
  'hasNext' => !$pager->isLastPage(),
);
