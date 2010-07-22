<?php if (count($activities) || isset($form)): ?>
<?php $params = array(
  'activities' => $activities,
  'gadget' => $gadget,
  'title' => __("SNS Member's %activity%", array(
    '%activity%' => $op_term['activity']->titleize()->pluralize()
  )),
  'moreUrl' => 'member/showAllMemberActivity'
) ?>
<?php if (isset($form)): ?>
<?php $params['form'] = $form ?>
<?php endif; ?>
<?php include_partial('default/activityBox', $params) ?>
<?php endif; ?>
