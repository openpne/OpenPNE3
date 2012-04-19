<div class="row">
  <div class="gadget_header span12"> <?php echo __('Invite a friend to %1%', array('%1%' => $op_config['sns_name'])); ?> </div>
</div>
<div class="row">
<div class="alert alert-success">
<p><?php echo __('Sent.') ?></p>
</div>
</div>

<div class="row">
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
<?php echo $form->renderHiddenFields(); ?>
<input type="submit" name="submit" value="<?php echo __('Send'); ?>" class="btn btn-primary" />
</form>
</div>
