<?php
$options = array(
  'title' => __('%community% Members', array('%community%' => $op_term['community']->titleize())),
  'list' => $pager->getResults(),
  'crownIds' => $sf_data->getRaw('crownIds'),
  'link_to' => 'member/profile?id=',
  'pager' => $pager,
  'link_to_pager' => 'community/memberList?page=%d&id='.$community->getId(),
);
op_include_parts('photoTable', 'communityMembersList', $options)
?>
