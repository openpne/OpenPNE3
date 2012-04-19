<form action="<?php echo url_for('@member_editProfile'); ?>" method="post">
<div class="row">
  <div class="gadget_header span12"> <?php echo __('Edit Profile'); ?> </div>
</div>

<?php $errors = array(); ?>
<?php if ($memberForm->hasGlobalErrors()): ?>
<?php $errors[] = $memberForm->renderGlobalErrors(); ?>
<?php endif; ?>
<?php if ($profileForm->hasGlobalErrors()): ?>
<?php $errors[] = $profileForm->renderGlobalErrors(); ?>
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

<div class="row" style="margin-left: 0px;">
<table class="table-striped">
<?php foreach ($memberForm as $mf): ?>
<?php if (!$mf->isHidden()): ?>
<tr>
  <td>
    <div class="control-group<?php echo $mf->hasError()? ' error' : '' ?>">
      <label class="control-label"><?php echo $mf->renderLabel() ?></label>
      <div class="controls">
        <?php if ($mf->hasError()): ?>
        <span class="label label-important label-block"><?php echo __($mf->renderError()); ?></span>
        <?php endif ?>
        <?php echo $mf->render(array('class' => 'span12')) ?>
        <span class="help-block"><?php echo $mf->renderHelp(); ?></span>    
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach ($profileForm as $pf): ?>
<?php if (!$pf->isHidden()): ?>
<tr>
  <td>
    <div class="control-group<?php echo $pf->hasError()? ' error' : '' ?>">
      <label class="control-label"><?php echo $pf->renderLabel() ?></label>
      <div class="controls">
        <?php if ($pf->hasError()): ?>
        <span class="label label-important label-block"><?php echo __($pf->renderError()); ?></span>
        <?php endif ?>
        <?php if ($pf->getWidget()->getOption('widget') instanceof sfWidgetFormDate): ?>
        <?php echo $pf->render(array('class' => 'span4')) ?>
        <?php else: ?>
        <?php echo $pf->render(array('class' => 'span12')) ?>
        <?php endif ?>
        <span class="help-block"><?php echo $pf->renderHelp(); ?></span>    
      </div>
    </div>
  </td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<?php echo $memberForm->renderHiddenFields(); ?>
<?php echo $profileForm->renderHiddenFields(); ?>
</table>
<div class="center">
<input type="submit" name="submit" value="<?php echo __('Send') ?>" class="btn btn-primary" />
<input type="reset" name="reset" value="<?php echo __('Reset') ?>" class="btn btn-danger" />
</div>

</form>
</div>
