<?php
$options->setDefault('button', __('Send'));
$options->setDefault('url', url_for(sfContext::getInstance()->getRouting()->getCurrentInternalUri()));
$options->setDefault('method','post');
$options->setDefault('firstRow', '');
$options->setDefault('lastRow', '');
?>

<form action="<?php echo $options->getRaw('url') ?>" method="<?php echo $options['method'] ?>"<?php if (!empty($options['isMultipart'])): ?> enctype="multipart/form-data"<?php endif; ?>>
<?php include_customizes($id, 'formTop') ?>

<?php if (isset($options['body'])): ?>
<div class="block">
<?php echo $options['body'] ?>
</div>
<?php endif ?>

<table>
<?php include_customizes($id, 'firstRow') ?>
<?php echo $options->getRaw('firstRow') ?>
<?php
$forms = ($options['form'] instanceof sfForm) ? array($options['form']) : $options['form'];

foreach ($forms as $form)
{
  if ($form->hasGlobalErrors())
  {
    echo '<tr>'."\n"
      . '<td colspan="2">'."\n";
    echo $form->renderGlobalErrors();
    echo '</td>'."\n"
      . '</tr>'."\n";
  }

  foreach ($form as $field)
  {
    if ($field->isHidden()) continue;

    $attributes = array();
    $widget = $field->getWidget();

    if ($widget instanceof sfWidgetFormInput)
    {
      $attributes = array('class' => sprintf('input_%s', $widget->getOption('type')));
    }
    elseif ($widget instanceof sfWidgetFormFilterInput)
    {
      $attributes = array('class' => 'input_text');
    }
    elseif ($widget instanceof sfWidgetFormChoice)
    {
      if ($widget->getRenderer() instanceof sfWidgetFormSelectRadio)
      {
        echo '<tr>'."\n"
           . '  <th>'.$field->renderLabel().'</th>'."\n"
           . '  <td>'.$field->renderError().'<div id="'.$field->renderId().'">'.$field->render(array('class' => 'input_radio')).'</div></td>'."\n"
           . '</tr>'."\n";
        continue;
      }
    }
    elseif ($widget instanceof opWidgetFormDate)
    {
      echo '<tr>'."\n"
         . '  <th>'.$field->renderLabel().'</th>'."\n"
         . '  <td>'.$field->renderError().'<div id="'.$field->renderId().'">'.$field->render().'</div></td>'."\n"
         . '</tr>'."\n";
      continue;
    }

    if ($widget instanceof opWidgetFormProfile)
    {
      $widget->setOption('template', '<div class="input">%input%</div>'."\n".'<div class="publicFlag">%public_flag%</div>');
    }

    echo $field->renderRow($attributes);
  }
}
?>
<?php echo $options->getRaw('lastRow') ?>
<?php include_customizes($id, 'lastRow') ?>
</table>

<div class="operation">
<ul class="moreInfo button">
<li>
<?php foreach ($forms as $form) echo $form->renderHiddenFields() ?><input type="submit" class="input_submit" value="<?php echo $options['button'] ?>" />
</li>
</ul>
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
