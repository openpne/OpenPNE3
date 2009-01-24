<?php op_include_box('noFriend', __('フレンドがいません。'), array('title' => __('フレンドリスト'))) ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
