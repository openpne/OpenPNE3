<?php op_mobile_page_title($id === $sf_user->getMemberId() ? __('My Activities') : __('Activities of %0%', array('%0%' => $member->getName()))) ?>
<?php if ($pager->getNbResults()): ?>
<center>
<?php op_include_pager_total($pager) ?>
</center>
<?php include_partial('default/activityBox', array(
  'title' => '',
  'activities' => $pager->getResults())
) ?>
<?php op_include_pager_navigation($pager, 'member/showActivity?page=%d&id='.$id) ?>
<?php else: ?>
<?php op_include_parts('box', 'ActivityBox', array(
  'body' => __('There is no activity.'),
)) ?>
<?php endif; ?>
