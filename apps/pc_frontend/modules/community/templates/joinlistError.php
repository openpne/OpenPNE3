<?php op_include_box('noJoinCommunity', __('You don\'t have any joined %community%.', array('%community%' => $op_term['community']->pluralize())), array('title' => __('Joined %community%', array('%community%' => $op_term['community']->pluralize()->titleize())))) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
