<?php foreach ($forms as $form) : ?>

<?php echo form_tag(url_for(sprintf('@login'.'?%s=%s', opAuthForm::AUTH_MODE_FIELD_NAME, $form->getAuthMode()))) ?>

<?php $errors = array(); ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php $errors[] = $form->renderGlobalErrors(); ?>
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
<?php foreach ($form as $field): ?>
<?php if (!$field->isHidden()): ?>
<?php if ('checkbox' === $field->getWidget()->getOption('type')): ?>
  <hr class="toumei">
  <div class="span9">
  <?php echo $field->renderLabel(); ?>
  </div>
  <div class="span3 <?php echo $field->hasError() ? 'clearfix error' : '' ?>">
    <?php if ($field->hasError()): ?>
    <span class="label label-important"><?php echo __($field->renderError()); ?>
    <?php endif; ?>
    <?php echo $field->render(); ?>
    <span class="help-block"><?php echo $field->renderHelp(); ?></span>
  </div>

  <hr class="toumei">
  <hr class="toumei">
<?php else: ?>
  <div class="span12">
  <?php echo $field->renderLabel(); ?>
  </div>
  <div class="span12 <?php echo $field->hasError() ? 'clearfix error' : '' ?>">
    <?php if ($field->hasError()): ?>
    <span class="label label-important"><?php echo __($field->renderError()) ?></span>
    <?php endif ?>
    <?php if (in_array($field->getWidget()->getOption('type'), array('text', 'password'))): ?>
    <?php echo $field->render(array('class' => 'span12')); ?>
    <?php else: ?>
    <?php echo $field->render(); ?>
    <?php endif; ?>
    <span class="help-block"><?php echo $field->renderHelp(); ?></span>
  </div>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
</div>

<div class="row">
<div class="span12">
<input type="submit" name="submit" value="<?php echo __('Login'); ?>" class="btn btn-primary span12 btn320" />
<?php echo $form->renderHiddenFields(); ?>
</form>
<?php if ($form->getAuthAdapter()->getAuthConfig('invite_mode') == 2
  && opToolkit::isEnabledRegistration('pc')
  && $form->getAuthAdapter()->getAuthConfig('self_invite_action')) : ?>
<?php echo link_to(__('Register'), $form->getAuthAdapter()->getAuthConfig('self_invite_action')) ?>
<?php endif; ?>
</div>

<?php endforeach; ?>
