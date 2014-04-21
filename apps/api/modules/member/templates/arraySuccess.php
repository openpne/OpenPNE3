<?php

$data = array();

foreach ($members as $member)
{
  $data[] = op_api_member($member);
}

return array(
  'status' => 'success',
  'data' => $data,
  'page' => array(
    'current' => $pager->getPage(),
    'isNext' => ($pager->getPage() < $pager->getLastPage()),
    'next' => $pager->getNextPage(),
    'last' => $pager->getLastPage(),
  ),
);
