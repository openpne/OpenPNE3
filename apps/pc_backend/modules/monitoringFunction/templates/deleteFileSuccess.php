<?php slot('submenu') ?>
<?php include_component('monitoringFunction', 'subMenu', array('nowUri' => 'monitoringFunction/fileList')) ?>
<?php end_slot() ?>

<?php slot('title', __('ファイル削除の確認')) ?>

<div class="fileListTable">
<p id="c01" class="caution"><?php echo __('本当に削除してもよろしいですか？') ?></p>
<?php include_partial('monitoringFunction/fileInfo', array('files' => array($file), 'deleteBtn' => false)) ?>
<br class="clear"/>
<form action="<?php url_for('monitoringFunction/deleteFile?id='.$file->getId()) ?>" method="post">
<input type="submit" value="<?php echo __('削除する') ?>"/>
</form>
</div>
