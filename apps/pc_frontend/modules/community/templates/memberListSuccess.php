<?php
$options = array(
  'title' => __('Community Members'),
  'list' => $pager->getResults(),
  'link_to' => 'member/profile?id=',
  'pager' => $pager,
  'link_to_pager' => 'community/memberList?page=%d&id='.$community->getId(),
);
op_include_parts('photoTable', 'communityMembersList', $options)
?>
