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
<div class="alert-message block-message error">
<a class="close" href="#">x</a>
<?php foreach ($errors as $error): ?>
<p><?php echo __($error) ?></p>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>

<div class="row">
<table class="zebra-striped">
<?php foreach ($memberForm as $mf): ?>
<?php if (!$mf->isHidden()): ?>
<tr>
  <th><?php echo $mf->renderLabel(); ?></th>
  <td>
  <div class="<?php echo $mf->hasError() ? 'clearfix error' : '' ?>">
    <?php if ($mf->hasError()): ?>
    <span class="label important"><?php echo __($mf->getError()); ?></span>
    <?php endif ?>
    <?php echo $mf->render(array('class' => 'span16')) ?>
    <span class="help-block"><?php echo $mf->renderHelp(); ?></span>
  </div>
  </td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<?php foreach ($profileForm as $pf): ?>
<?php if (!$pf->isHidden()): ?>
<tr>
  <th><?php echo $pf->renderLabel(); ?></th>
  <td>
  <div class="<?php $mf->hasError() ? 'clearfix error' : '' ?>">
    <?php if ($mf->hasError()): ?>
    <span class="label important"><?php echo __($pf->getError()); ?></span>
    <?php endif ?>
    <?php if ($pf->getWidget()->getOption('widget') instanceof sfWidgetFormDate): ?>
    <?php echo $pf->render(array('class' => 'span8')) ?>
    <?php else: ?>
    <?php echo $pf->render(array('class' => 'span16')) ?>
    <?php endif ?>
    <span class="help-block"><?php echo $pf->renderHelp() ?></span>
  </div>
  </td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<?php echo $memberForm->renderHiddenFields(); ?>
<?php echo $profileForm->renderHiddenFields(); ?>
</table>
<input type="submit" name="subtmi" value="<?php echo __('Send') ?>" class="btn primary" />
</form>
</div>
