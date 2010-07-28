<?php $title = $id === $sf_user->getMemberId() ? __('My %activity%', array(
  '%activity%' => $op_term['activity']->pluralize()->titleize()
)) : __('%activity% of %0%', array(
  '%activity%' => $op_term['activity']->pluralize()->titleize(),
  '%0%' => $member->getName()
)) ?>
<?php if ($pager->getNbResults()): ?>
<?php slot('pager') ?>
<?php op_include_pager_navigation($pager, 'member/showActivity?page=%d&id='.$id) ?>
<?php end_slot(); ?>
<?php include_slot('pager') ?>
<?php include_partial('default/activityBox', array(
  'title' => $title,
  'activities' => $pager->getResults())
) ?>
<?php include_slot('pager') ?>
<?php else: ?>
<?php op_include_parts('box', 'ActivityBox', array(
  'body' => __('There is no %activity%.'),
  'title' => $title
)) ?>
<?php endif; ?>
