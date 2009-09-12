<?php slot('submenu') ?>
<?php include_component('monitoringFunction', 'subMenu', array('nowUri' => 'monitoringFunction/fileList')) ?>
<?php end_slot() ?>

<?php slot('title', __('アップロードファイルリスト')) ?>

<?php if (!$pager->getNbResults()): ?>
<?php echo __('アップロードファイルは存在しません。') ?></p>
<?php else: ?>
<?php ob_start() ?>
<p><?php op_include_pager_navigation($pager, 'monitoringFunction/list?page=%d') ?></p>
<?php $pagerNavi = ob_get_flush() ?>
<div class="fileListTable">
<?php include_partial('fileInfo', array('files' => $pager->getResults(), 'deleteBtn' => true)) ?>
</div>
<br class="clear"/>
<?php echo $pagerNavi ?>
<?php endif; ?>
