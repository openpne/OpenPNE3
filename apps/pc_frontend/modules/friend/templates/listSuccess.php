<?php
$options = array(
  'title' => __('%friend% List', array('%friend%' => $op_term['friend']->titleize())),
  'list' => $pager->getResults(),
  'link_to' => 'member/profile?id=',
  'pager' => $pager,
  'link_to_pager' => 'friend/list?page=%d&id='.$id,
);
op_include_parts('photoTable', 'friendList', $options)
?>
