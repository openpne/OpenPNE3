<?php slot('submenu') ?>
<?php include_component('monitoring', 'submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('画像削除の確認')) ?>

<div class="imageListTable">
<p id="c01" class="caution"><?php echo __('本当に削除してもよろしいですか？') ?></p>
<?php include_partial('monitoring/imageInfo', array('image' => $image, 'deleteBtn' => FALSE)) ?>
<br class="clear"/>
<?php echo $form->renderFormTag(url_for('monitoring/deleteImage?id='.$image->getId())) ?>
<?php echo $form ?>
<input type="submit" value="<?php echo __('削除する') ?>"/>
</form>
</div>
