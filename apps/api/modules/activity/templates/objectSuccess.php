<?php

$acEntity = op_api_activity($activity);

$replies = $activity->getReplies();
if (0 !== count($replies))
{
  $acEntity['replies'] = array();

  foreach ($replies as $reply)
  {
    $acEntity['replies'][] = op_api_activity($reply);
  }
}

return array(
  'status' => 'success',
  'data' => $acEntity,
);
