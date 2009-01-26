<?php op_include_box('manageFriendWarning', __('You don\'t have any friends.'), array('title' => __('Friend List'))) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
