<?php

$data = array();

foreach ($pager as $member)
{
  $data[] = op_api_member($member);
}

return array(
  'status' => 'success',
  'data' => $data,
);
