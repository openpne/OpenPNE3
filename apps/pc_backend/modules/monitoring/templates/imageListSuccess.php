<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php slot('title', 'アップロード画像リスト') ?>

<?php if (!$pager->getNbResults()): ?>
<?php echo __('アップロード画像は存在しません。') ?></p>
<?php else: ?>
<?php
$params = array();
$params['uri'] = url_for('monitoring/imageList');
$params['method'] = 'get';
$params['title'] = __('表示件数');
$params['params'] = array(20, 50, 100, 500);
$params['unit'] = '件';
$params['name'] = 'size';
$params['default'] = $size;
$params['caution'] = __('※表示件数を多くすると処理が重くなり、サーバーに負荷がかかります。');
include_partial('global/changePageSize', array('params' => $params));
?>

<p><?php op_include_pager_navigation($pager, 'monitoring/imageList?page=%d&size='.$size) ?></p>
<div class="imageListTable">
<?php foreach ($pager->getResults() as $image): ?>
<?php include_partial('imageInfo', array('image' => $image, 'deleteBtn' => true)) ?>
<?php endforeach; ?>
<br class="clear"/>
</div>
<p><?php op_include_pager_navigation($pager, 'monitoring/imageList?page=%d&size='.$size) ?></p>
<?php endif; ?>
