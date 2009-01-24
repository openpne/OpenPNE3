<?php op_include_box('noJoinCommunity', __('参加しているコミュニティはありません。'), array('title' => __('参加コミュニティ'))) ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
