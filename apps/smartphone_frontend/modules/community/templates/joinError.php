<?php if ($isCommunityMember): ?>
<?php $body =  __('You are already joined to this %community%.') ?>
<?php else: ?>
<?php $body =  __('You have already sent the participation request to this %community%.') ?>
<?php endif; ?>
<?php op_include_box('error', $body, array('title' => __('Errors'))) ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
