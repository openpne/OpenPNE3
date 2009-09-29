<?php
$options = array(
  'title' => __('%community% List', array('%community%' => $op_term['community']->titleize())),
  'list' => $pager->getResults(),
  'crownIds' => $sf_data->getRaw('crownIds'),
  'link_to' => 'community/home?id=',
  'pager' => $pager,
  'link_to_pager' => 'community/joinlist?page=%d&id='.$member->getId(),
);
op_include_parts('photoTable', 'communityList', $options)
?>
