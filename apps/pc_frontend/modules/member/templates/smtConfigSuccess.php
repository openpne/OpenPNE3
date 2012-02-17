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
<div class="alert-message block-message error">
<?php foreach ($errors as $error): ?>
<p><?php echo __($error) ?></p>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<div class="row span12" style="margin-left: 5px;">
<?php foreach ($form as $f): ?>
<?php if (!$f->isHidden()): ?>
<div class="clearfix <?php $f->hasError() ? 'error' : '' ?>">
<label for="xlInput3" class="span12"><?php echo $f->renderLabel() ?></label>
<?php echo $f->render(array('class' => 'span12')) ?>
<span class="help-block"><?php echo $f->renderHelp() ?></span>
</div>
<?php endif; ?>
<?php endforeach; ?>
<input type="submit" name="submit" value="<?php echo __('Send') ?>" class="btn danger" />
<?php echo $form->renderHiddenFields(); ?>
</form>
</div>
<?php else: ?>
<?php echo __('Please select the item that wants to be set from the menu.'); ?>
<?php endif; ?>

<div class="row span12" style="margin-left: 5px;">
<?php foreach ($lists as $list): ?>
<div class="span6" style="margin: 0px;"><?php echo $list; ?></div>
<?php endforeach; ?>
</div>
