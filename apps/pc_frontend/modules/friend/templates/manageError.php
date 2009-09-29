<?php op_include_box('manageFriendWarning', __('You don\'t have any %friend%.', array('%friend%' => $op_term['friend']->pluralize())), array('title' => __('%friend% List', array('%friend%' => $op_term['friend']->titleize())))) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
