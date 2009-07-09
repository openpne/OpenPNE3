<?php
$options->setDefault('button', __('Send'));
$options->setDefault('url', url_for(sfContext::getInstance()->getRouting()->getCurrentInternalUri()));
$options->setDefault('method','post');
?>

<?php if ($options['form'] instanceof opAuthRegisterForm): ?>
<?php echo $options['form']->renderFormTag($options['url'], array('method' => $options['method'])) ?>
<?php $forms = $options['form']->getAllForms() ?>
<?php else: ?>
<form action="<?php echo $options['url'] ?>" method="<?php echo $options['method'] ?>">
<?php $forms = ($options['form'] instanceof sfForm) ? array($options['form']): $options['form'] ?>
<?php endif; ?>
<?php include_customizes($id, 'formTop') ?>
<?php foreach ($forms as $form): ?>
<?php if ($form->hasGlobalErrors()): ?>
<?php echo $form->renderGlobalErrors(); ?>
<?php endif; ?>
<?php echo $form->renderHiddenFields() ?>
<?php
foreach ($form as $field)
{
  if ($field->isHidden()) continue;

  $attributes = array();
  $widget = $field->getWidget();

  if ($widget instanceof opWidgetFormProfile)
  {
    $widget = $widget->getOption('widget');
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

  echo $field->renderRow($attributes);
}
?>
<?php endforeach; ?>
<?php include_customizes($id, 'lastRow') ?>
<?php if (!empty($options['align'])): ?>
<div align="<?php echo $options['align'] ?>">
<?php else: ?>
<div>
<?php endif; ?>
<input type="submit" value="<?php echo $options['button'] ?>">
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
