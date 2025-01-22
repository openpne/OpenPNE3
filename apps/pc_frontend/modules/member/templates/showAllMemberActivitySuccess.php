<?php if ($pager->getNbResults() || isset($form)): ?>
<?php slot('pager') ?>
<?php if ($pager->getNbResults()): ?>
<?php op_include_pager_navigation($pager, 'member/showAllMemberActivity?page=%d&id='.$id) ?>
<?php endif; ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<?php $params = array(
  'title' => __("SNS Member's %activity%", array(
    '%activity%' => $op_term['activity']->titleize()->pluralize()
  )),
  'activities' => $pager->getResults(),
  'activityMessage' => $activityMessage,
) ?>
<?php if (isset($form)): ?>
<?php $params['form'] = $form ?>
<?php endif; ?>
<?php include_partial('default/activityBox', $params) ?>
<?php include_slot('pager') ?>
<?php else: ?>
<?php op_include_parts('box', 'ActivityBox', array(
  'body' => __('There is no %activity%.'),
  'title' => $title
)) ?>
<?php endif; ?>
