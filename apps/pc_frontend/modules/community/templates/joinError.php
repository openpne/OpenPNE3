<?php op_include_box('alreadyJoinCommunity', __('既にコミュニティに参加済みです。'), array('title' => __('エラー'))) ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
