<?php include_box('notFavorite', __('Favorite'), __('There is not registration of the favorite yet.')); ?>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
