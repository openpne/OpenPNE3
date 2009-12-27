<?php if (count($activities)): ?>
<?php $params = array(
  'activities' => $activities,
  'gadget' => $gadget,
  'title' => $isMine ? __('My Activities') : __('Activities of %0%', array('%0%' => $member->getName())),
  'moreUrl' => 'member/showActivity?id='.$member->getId(),
) ?>
<?php include_partial('default/activityBox', $params) ?>
<?php endif; ?>
