<?php if (count($activities)): ?>
<?php $params = array(
  'activities' => $activities,
  'gadget' => $gadget,
  'title' => $isMine ? __('My %activity%', array(
    '%activity%' => $op_term['activity']->titleize()->pluralize()
  )) : __('%activity% of %0%', array(
    '%0%' => $member->getName(),
    '%activity%' => $op_term['activity']->titleize()->pluralize()
  )),
  'moreUrl' => 'member/showActivity?id='.$member->getId(),
) ?>
<?php include_partial('default/activityBox', $params) ?>
<?php endif; ?>
