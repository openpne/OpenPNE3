<?php if ($isAdmin): ?>
<?php op_include_box('admin', __('管理者は退会できません。'), array('title' => __('エラー'))) ?>
<?php else: ?>
<?php op_include_box('nonAdmin', __('まだコミュニティに参加していません。'), array('title' => __('エラー'))) ?>
<?php endif; ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
