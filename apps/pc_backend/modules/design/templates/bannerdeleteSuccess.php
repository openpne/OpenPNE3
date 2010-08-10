<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php $form = new BaseForm() ?>
<h2><?php echo __('Delete a banner image') ?></h2>
<p><?php echo __('Delete truly this banner image?') ?></p>
<form action="" method="post">
<td colspan="2">
<input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
<input type="submit" value="<?php echo __('Delete') ?>" />
</td>
</form>
