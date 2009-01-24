<?php op_include_box('manageFriendWarning', __('フレンド登録がありません'), array('title' => __('フレンド管理'))) ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
