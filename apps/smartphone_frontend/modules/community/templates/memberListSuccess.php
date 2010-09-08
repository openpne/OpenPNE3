<?php
$options = array(
  'title' => __('%community% Members', array('%community%' => $op_term['community']->titleize())),
  'list' => $pager->getResults(),
  'crownIds' => $sf_data->getRaw('crownIds'),
  'link_to' => '@member_profile?id=',
  'pager' => $pager,
  'link_to_pager' => '@community_memberList?page=%d&id='.$community->getId(),
  'use_op_link_to_member' => true,
);
op_include_parts('photoTable', 'communityMembersList', $options)
?>
