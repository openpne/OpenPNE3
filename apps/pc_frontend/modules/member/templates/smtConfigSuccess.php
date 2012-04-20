<?php
$lists = array();
foreach ($categories as $key => $value)
{
  if (count($value))
  {
    $lists[$key] = link_to(__($categoryCaptions[$key]), '@member_config?category='.$key);
  }
}
?>
<?php if($categoryName): ?>
<div class="row">
  <div class="gadget_header span12"><?php echo __($categoryCaptions[$categoryName]); ?></div>
</div>
<?php else: ?>
<div class="row">
  <div class="gadget_header span12"><?php echo __('Change Settings'); ?></div>
</div>
<?php endif; ?>

<?php if ($categoryName): ?>
<?php echo form_tag(url_for('@member_config?category='.$categoryName)) ?>
<?php $errors = array(); ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php $errors[] = $form->renderGlobalErrors(); ?>
<?php endif; ?>
<?php if ($errors): ?>
<div class="row">
<div class="alert alert-error">
<?php foreach ($errors as $error): ?>
<p><?php echo __($error) ?></p>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<div class="row" style="margin-left: 0px;">
<?php foreach ($form as $f): ?>
<?php if (!$f->isHidden()): ?>
<div class="control-group<?php echo $f->hasError()? ' error' : '' ?>">
  <label class="control-label"><?php echo $f->renderLabel() ?></label>
  <div class="controls">
    <?php if ($f->hasError()): ?>
    <span class="label label-important label-block"><?php echo __($f->renderError()); ?></span>
    <?php endif ?>
    <?php echo $f->render(array('class' => 'span12')) ?>
    <span class="help-block"><?php echo $f->renderHelp(); ?></span>    
  </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<input type="submit" name="submit" value="<?php echo __('Send') ?>" class="btn btn-danger" />
<?php echo $form->renderHiddenFields(); ?>
</form>
</div>
<?php else: ?>
<?php echo __('Please select the item that wants to be set from the menu.'); ?>
<?php endif; ?>

<div class="row">
<ul class="nav nav-tabs nav-stacked">
<?php foreach ($lists as $list): ?>
<li><?php echo $list ?></li>
<?php endforeach; ?>
</ul>
</div>
