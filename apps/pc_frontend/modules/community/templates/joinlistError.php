<?php op_include_box('noJoinCommunity', __('You don\'t have any joined communities.'), array('title' => __('Joined Communities'))) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
