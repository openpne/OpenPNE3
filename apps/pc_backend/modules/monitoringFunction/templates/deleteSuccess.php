<?php slot('submenu') ?>
<?php include_component('monitoringFunction', 'subMenu', array('nowUri' => 'monitoringFunction/index')) ?>
<?php end_slot() ?>

<?php slot('title', __('画像削除の確認')) ?>

<div class="imageListTable">
<p id="c01" class="caution"><?php echo __('本当に削除してもよろしいですか？') ?></p>
<?php include_partial('monitoringFunction/imageInfo', array('image' => $image, 'deleteBtn' => FALSE)) ?>
<br class="clear"/>
<form action="<?php url_for('monitoringFunction/delete?id='.$image->getId()) ?>" method="post">
<input type="submit" value="<?php echo __('削除する') ?>"/>
</form>
</div>
