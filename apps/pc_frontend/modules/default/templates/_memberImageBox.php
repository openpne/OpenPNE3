<?php
$moreInfo = array();
if ($member->getId() === $id)
{
  $moreInfo[] = link_to(__('Edit Photo'), 'member/configImage');
  $moreInfo[] = link_to(__('Show Profile'), 'member/profile');
}
elseif ($member->getImageFileName())
{
  $moreInfo[] = link_to(__('Show more Photos'), 'friend/showImage?id='.$member->getId());
}

$options = array(
  'object'   => $member,
  'moreInfo' => $moreInfo,
);
op_include_parts('memberImageBox', 'memberImageBox_'.$gadget->getId(), $options);
