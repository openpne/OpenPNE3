<?php $title = __('%activity% of %my_friend%', array(
  '%activity%' => $op_term['activity']->pluralize()->titleize(),
  '%my_friend%' => $op_term['my_friend']->pluralize()->titleize()
)) ?>
<?php if ($pager->getNbResults() || isset($form)): ?>
<?php slot('pager') ?>
<?php if ($pager->getNbResults()): ?>
<?php op_include_pager_navigation($pager, 'friend/showActivity?page=%d') ?>
<?php endif; ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<?php $params = array(
  'title' => $title,
  'activities' => $pager->getResults(),
) ?>
<?php if (isset($form)): ?>
<?php $params['form'] = $form; ?>
<?php endif; ?>
<?php include_partial('default/activityBox', $params) ?>
<?php include_slot('pager') ?>
<?php else: ?>
<?php op_include_parts('box', 'ActivityBox', array(
  'body' => __('There is no %activity%.'),
  'title' => $title
)) ?>
<?php endif; ?>
