<?php slot('submenu') ?>
<?php include_component('monitoringFunction', 'subMenu', array('nowUri' => 'monitoringFunction/index')) ?>
<?php end_slot() ?>

<?php slot('title', 'アップロード画像リスト') ?>

<?php if (!$pager->getNbResults()): ?>
<?php echo __('アップロード画像は存在しません。') ?></p>
<?php else: ?>
<?php ob_start() ?>
<p><?php op_include_pager_navigation($pager, 'monitoringFunction/list?page=%d') ?></p>
<?php $pagerNavi = ob_get_flush() ?>
<div class="imageListTable">
<?php foreach ($pager->getResults() as $image): ?>
<?php include_partial('imageInfo', array('image' => $image, 'deleteBtn' => true)) ?>
<?php endforeach; ?>
<br class="clear"/>
</div>
<?php echo $pagerNavi ?>
<?php endif; ?>
