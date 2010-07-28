<?php
$moreInfo = array();
if ($member->getId() === $id)
{
  $moreInfo[] = link_to(__('Edit Photo'), '@member_config_image');
  $moreInfo[] = link_to(__('Show Profile'), '@member_profile_mine');
}
elseif ($member->getImageFileName())
{
  $moreInfo[] = link_to(__('Show more Photos'), '@friend_show_image?id='.$member->getId());
}

$options = array(
  'object'   => $member,
  'moreInfo' => $moreInfo,
);
op_include_parts('memberImageBox', 'memberImageBox_'.$gadget->getId(), $options);
