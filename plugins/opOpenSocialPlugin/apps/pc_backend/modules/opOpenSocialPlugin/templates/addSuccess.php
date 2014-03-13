<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Add App') ?>
<?php end_slot() ?>

<?php include_partial('bottomSubmenu') ?>

<?php echo $form->renderFormTag(url_for('opOpenSocialPlugin/add')) ?>
<table>
<?php echo $form ?>
<td colspan="2"><input type="submit" value="<?php echo __('Add') ?>" /></td>
</table>
</form>
