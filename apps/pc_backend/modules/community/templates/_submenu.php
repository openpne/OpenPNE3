<li><?php echo link_to(__('%community% List', array('%community%' => $op_term['community']->titleize())), 'community/list') ?></li>
<li><?php echo link_to(__('%community% Category Configuration', array('%community%' => $op_term['community']->titleize())), 'community/categoryList') ?></li>
<li><?php echo link_to(__('Default %community% Configuration', array('%community%' => $op_term['community']->titleize())), 'community/defaultCommunityList') ?></li>
