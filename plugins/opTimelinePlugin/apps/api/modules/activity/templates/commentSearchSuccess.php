<?php

$data = array();

foreach($replies as $reply)
{
  $data[] = op_api_activity($reply);
}

return array(
  'status' => 'success',
  'data' => $data,
);
