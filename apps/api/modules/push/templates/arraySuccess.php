<?php

$data = array();

foreach ($notifications as $notification)
{
  $data[] = op_api_notification($notification);
}

return array(
  'status' => 'success',
  'data' => $data
);
