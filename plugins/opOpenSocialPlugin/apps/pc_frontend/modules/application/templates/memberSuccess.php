<?php
$options = array(
  'title' => __('Members that has "%0%"', array('%0%' => $application->getTitle())),
  'list' => $pager->getResults(),
  'link_to' => 'member/profile?id=',
  'pager' => $pager,
  'link_to_pager' => '@application_member?page=%d&id='.$application->getId(),
  'moreInfo' => array(link_to(__('App Information'), '@application_info?id='.$application->getId()))
);
op_include_parts('photoTable', 'membersList', $options)
?>
