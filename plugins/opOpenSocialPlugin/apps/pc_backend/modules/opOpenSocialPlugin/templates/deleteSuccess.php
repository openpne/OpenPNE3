<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title') ?>
<?php echo __('Delete App') ?>
<?php end_slot() ?>

<?php use_helper('Javascript') ?>
<p><?php echo __('Do you delete this app?') ?></p>
<p><?php echo $application->getTitle() ?></p>
<?php $form = new sfForm() ?>
<?php echo $form->renderFormTag(url_for('opOpenSocialPlugin/delete?id='.$sf_request->getParameter('id'))) ?>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Delete') ?>">
</form>
<?php echo link_to_function(__('Back to previous page'), 'history.back()') ?>
