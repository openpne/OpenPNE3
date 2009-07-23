<?php
$options = array(
  'object'   => $member,
);
$photoLink = '';
if ($relation->isSelf())
{
  $photoLink = link_to(__('Edit Photo'), 'member/configImage');
}
elseif ($member->getImageFileName())
{
  $photoLink = link_to(__('Show Photo'), 'friend/showImage?id='.$member->getId());
}
$options['moreInfo'] = array(
  $photoLink,
  link_to(__('Show Profile'), 'member/profile'),
);
op_include_parts('memberImageBox', 'memberImageBox_'.$gadget->getId(), $options);
