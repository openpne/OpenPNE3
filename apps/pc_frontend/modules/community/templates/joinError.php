<?php op_include_box('alreadyJoinCommunity', __('You are already joined to this community.'), array('title' => __('Erros'))) ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('Back to previous page'), 'history.back()') ?></p>
