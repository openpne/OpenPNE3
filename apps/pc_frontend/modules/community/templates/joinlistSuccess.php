<?php
$options = array(
  'title' => __('Communities List'),
  'list' => $pager->getResults(),
  'link_to' => 'community/home?id=',
  'pager' => $pager,
  'link_to_pager' => 'community/joinlist?page=%d&id='.$member->getId(),
);
op_include_parts('photoTable', 'communityList', $options)
?>
