<?php decorate_with('layoutC') ?>
<div id="formMessageDeleteList" class="dparts box">
<div class="parts">
<div class="partsHeading">
<h3><?php echo __('Delete messages') ?></h3>
</div>
<div class="block">
<p><?php echo __('Do you delete messages?') ?></p>
<form action="<?php echo url_for('@'.$messageType.'List') ?>" method="post">
<?php slot('form') ?>
<?php echo $form->renderHiddenFields() ?>
<?php
foreach($form->getValue('message_ids') as $s_key => $s_value)
{
  $tw = new sfWidgetFormInputHidden(array(), array());
  $tw->addOption('name_format',$form->getWidgetSchema()->getNameFormat());
  $tn =  strtr($form->getWidgetSchema()->getNameFormat(),array('%s' =>"message_ids"));
  echo  $tw->render($tn.'['.$s_key.']', $s_value);
}
?>
<?php end_slot(); ?>


<?php slot('form_global_error') ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php echo $form->renderGlobalErrors() ?>
<?php endif; ?>
<?php end_slot(); ?>
<?php if (get_slot('form_global_error')): ?>
<?php echo get_slot('form_global_error') ?><br><br>
<?php endif; ?>

<?php include_slot('form') ?>
<div class="operation">
<ul class="moreInfo button">
<li>
<input class="input_submit" type="submit" value="<?php echo __('Delete') ?>" />
<input type="hidden" name="only_hidden" value="true">
<input type="hidden" name="page" value="<?php echo $pager->getPage() ?>">
</li>
</ul>
</div>
</form>
</div>
</div></div>

<?php use_helper('Javascript') ?>
<?php op_include_line('backLink', link_to_function(__('Back to previous page'), 'history.back()')) ?>
