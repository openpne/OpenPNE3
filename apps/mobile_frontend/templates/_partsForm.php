<?php
$options->setDefault('button', __('Send'));
$options->setDefault('url', $sf_request->getCurrentUri());
$options->setDefault('method','post');
$options->setDefault('mark_required_field', true);
?>

<?php if ($options['form'] instanceof opAuthRegisterForm): ?>
<?php echo $options['form']->renderFormTag($options['url'], array('method' => $options['method'])) ?>
<?php $forms = $options['form']->getAllForms() ?>
<?php else: ?>
<form action="<?php echo $options['url'] ?>" method="<?php echo $options['method'] ?>">
<?php $forms = ($options['form'] instanceof sfForm) ? array($options['form']): $options['form'] ?>
<?php endif; ?>

<?php include_customizes($id, 'formTop') ?>

<?php $hasRequiredField = false ?>

<?php slot('form') ?>
<?php foreach ($forms as $form): ?>
<?php echo $form->renderHiddenFields() ?>
<?php
foreach ($form as $name => $field)
{
  if ($field->isHidden()) continue;
  $attributes = array();
  $widget = $field->getWidget();
  $validator = $form->getValidator($name);

  if ($widget instanceof sfWidgetFormInputPassword)
  {
    $widget = opToolkit::appendMobileInputModeAttributesForFormWidget($widget, 'alphabet');
  }

  if ($widget instanceof opWidgetFormProfile)
  {
    $widget = $widget->getOption('widget');
    $validator = $validator->getOption('validator');
  }

  if ($widget instanceof sfWidgetFormChoice)
  {
    if ($widget->getRenderer() instanceof sfWidgetFormSelectRadio || $widget->getRenderer() instanceof sfWidgetFormSelectCheckbox)
    {
      $widget->setOption('renderer_options', 
        array_merge(array(
          'formatter' => array('opWidgetFormSelectFormatterMobile', 'formatter'),
          'separator' => "<br>\n"
        ), $widget->getOption('renderer_options'))
      );
    }
  }
  elseif ($widget instanceof sfWidgetFormSelectRadio || $widget instanceof sfWidgetFormSelectCheckbox)
  {
    $widget->setOption('formatter', array('opWidgetFormSelectFormatterMobile', 'formatter'));
    $widget->setOption('separator', "<br>\n");
  }

  if ($options['mark_required_field'] 
    && !($validator instanceof sfValidatorPass)
    && !($validator instanceof sfValidatorSchema)
    && $validator->getOption('required'))
  {
    echo sprintf('<font color="%s">*</font>', opColorConfig::get('core_color_22'));
    $hasRequiredField = true;
  }

  echo $field->renderRow($attributes);
}
?>
<?php endforeach; ?>
<?php end_slot(); ?>

<?php if ($hasRequiredField): ?>
<?php echo __('%0% is required field.', array('%0%' => sprintf('<font color="%s">*</font>', opColorConfig::get('core_color_22')))) ?>
<hr color="<?php echo opColorConfig::get('core_color_11') ?>">
<?php endif; ?>

<?php slot('form_global_error') ?>
<?php foreach ($forms as $form): ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php echo $form->renderGlobalErrors() ?>
<?php endif; ?>
<?php endforeach; ?>
<?php end_slot(); ?>
<?php if (get_slot('form_global_error')): ?>
<?php echo get_slot('form_global_error') ?><br><br>
<?php endif; ?>

<?php include_slot('form') ?>

<?php if (!empty($options['align'])): ?>
<div align="<?php echo $options['align'] ?>">
<?php else: ?>
<div>
<?php endif; ?>
<input type="submit" value="<?php echo $options['button'] ?>">
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
