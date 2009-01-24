<?php op_include_box('error', __('IDが1のメンバーは退会することができません')); ?>

<?php use_helper('Javascript') ?>
<p><?php echo link_to_function(__('前のページに戻る'), 'history.back()') ?></p>
