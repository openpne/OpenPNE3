<?php if ($communityForm->isNew()): ?>
<?php echo form_tag(url_for('@community_edit'), 'multipart=true') ?>
<?php else: ?>
<?php echo form_tag(url_for('@community_edit?id='.$community->getId()), 'multipart=true') ?>
<?php endif; ?>
<div class="row">
<?php if ($communityForm->isNew()): ?>
  <div class="gadget_header span12"> <?php echo __('Create a new %community%'); ?> </div>
<?php else: ?>
  <div class="gadget_header span12"> <?php echo __('Edit the %community%'); ?> </div>
<?php endif; ?>
</div>

<?php $errors = array(); ?>
<?php if ($communityForm->hasGlobalErrors()): ?>
<?php $errors[] = $communityForm->renderGlobalErrors(); ?>
<?php endif; ?>
<?php if ($communityConfigForm->hasGlobalErrors()): ?>
<?php $errors[] = $communityConfigForm->renderGlobalErrors(); ?>
<?php endif; ?>
<?php if ($communityFileForm->hasGlobalErrors()): ?>
<?php $errors[] = $communityFileForm->renderGlobalErrors(); ?>
<?php endif; ?>
<?php if ($errors): ?>
<div class="row">
<div class="alert alert-error">
<a class="close" href="#">x</a>
<?php foreach ($errors as $error): ?>
<p><?php echo __($error) ?></p>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<div class="row">
<?php foreach ($communityForm as $cf): ?>
<?php if (!$cf->isHidden()): ?>
<div class="control-group<?php echo $cf->hasError()? ' error' : '' ?>">
  <label class="control-label"><?php echo $cf->renderLabel() ?></label>
  <div class="controls">
    <?php if ($cf->hasError()): ?>
    <span class="label label-important label-block"><?php echo __($cf->renderError()); ?></span>
    <?php endif ?>
    <?php echo $cf->render(array('class' => 'span12')) ?>
    <span class="help-block"><?php echo $cf->renderHelp(); ?></span>    
  </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
</div>

<div class="row">
<?php foreach ($communityConfigForm as $ccf): ?>
<?php if (!$ccf->isHidden()): ?>
<div class="control-group<?php echo $ccf->hasError()? ' error' : '' ?>">
  <label class="control-label"><?php echo $ccf->renderLabel() ?></label>
  <div class="controls">
    <?php if ($ccf->hasError()): ?>
    <span class="label label-important label-block"><?php echo __($ccf->renderError()); ?></span>
    <?php endif ?>
    <?php echo $ccf->render(array('class' => 'span12')) ?>
    <span class="help-block"><?php echo $ccf->renderHelp(); ?></span>    
  </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
</div>

<div class="row">
<?php foreach ($communityFileForm as $cff): ?>
<?php if (!$cff->isHidden()): ?>
<div class="control-group<?php echo $cff->hasError()? ' error' : '' ?>">
  <label class="control-label"><?php echo $cff->renderLabel() ?></label>
  <div class="controls">
    <?php if ($cff->hasError()): ?>
    <span class="label label-important label-block"><?php echo __($cff->renderError()); ?></span>
    <?php endif ?>
    <?php echo $cff->render(array('class' => 'span12')) ?>
    <span class="help-block"><?php echo $cff->renderHelp(); ?></span>    
  </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
</div>

<div class="row">
<div class="span12 center">
<input type="submit" name="submit" value="<?php echo __('Send') ?>" class="btn btn-primary" />
<?php echo $communityForm->renderHiddenFields(); ?>
<?php echo $communityConfigForm->renderHiddenFields(); ?>
<?php echo $communityFileForm->renderHiddenFields(); ?>
</form>
</div>
</div>

</div>
