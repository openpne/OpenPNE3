<?php if (count($activities) || isset($form)): ?>
<?php $params = array(
  'activities' => $activities,
  'gadget' => $gadget,
) ?>
<?php if (isset($form)): ?>
<?php $params['form'] = $form ?>
<?php endif; ?>
<?php include_partial('default/activityBox', $params); ?>
<?php endif; ?>
