<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('Reissue password') ?></h2>
<p><?php echo __('Change %1%\'s password', array('%1%' => link_to($member->getName(), app_url_for('pc_frontend', 'member/profile?id='.$member->getId())))) ?></p>

<?php echo $form->renderFormTag(url_for('member/reissuePassword?id='.$member->getId())) ?>
<table>
<?php echo $form ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('Change password') ?>" /></td></tr>
</table>
</form>

<?php use_helper('Javascript') ?>
<?php echo link_to_function(__('Return to previous page'), 'history.back()') ?>
