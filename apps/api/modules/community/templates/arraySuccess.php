<?php

$data = array();

foreach ($communities as $community)
{
  $data[] = op_api_community($community);
}

return array(
  'status' => 'success',
  'data' => $data,
);
