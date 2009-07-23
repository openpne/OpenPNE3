<?php
$photoLink = '';
if ($member->getId() === $id)
{
  $photoLink = link_to(__('Edit Photo'), 'member/configImage');
}
elseif ($member->getImageFileName())
{
  $photoLink = link_to(__('Show more Photos'), 'friend/showImage?id='.$member->getId());
}
$options = array(
  'object'   => $member,
  'moreInfo' => array(
    $photoLink,
    link_to(__('Show Profile'), 'member/profile'),
  ),
);
op_include_parts('memberImageBox', 'memberImageBox_'.$gadget->getId(), $options);
