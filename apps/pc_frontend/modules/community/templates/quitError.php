<?php if ($isAdmin): ?>
<?php op_include_box('admin', __('The administrator doesn\'t leave the community.'), array('title' => __('Errors'))) ?>
<?php else: ?>
<?php op_include_box('nonAdmin', __('You haven\'t joined this community yet.'), array('title' => __('Errors'))) ?>
<?php endif; ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
