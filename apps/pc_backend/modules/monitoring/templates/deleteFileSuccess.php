<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('ファイル削除の確認')) ?>

<div class="fileListTable">
<p id="c01" class="caution"><?php echo __('本当に削除してもよろしいですか？') ?></p>
<?php include_partial('monitoring/fileInfo', array('files' => array($file), 'deleteBtn' => false)) ?>
<br class="clear"/>
<?php echo $form->renderFormTag(url_for('monitoring/deleteFile?id='.$file->getId())) ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('削除する') ?>"/>
</form>
</div>
