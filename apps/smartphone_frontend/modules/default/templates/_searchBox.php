<?php op_include_parts('searchFormLine', 'searchLine_'.$gadget->getId(), array(
  'button' => __('Search'),
  'items' => array(
    'member' => __('Member'),
    'community' => __('%community%', array('%community%' => $op_term['community']->titleize())),
  ),
)) ?>
