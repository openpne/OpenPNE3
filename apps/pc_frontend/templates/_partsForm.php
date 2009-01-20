<?php
$options = array_merge(array(
  'button' => __('変更'),
), $sf_data->getRaw('options'));
?>
<?php if (!empty($options['body'])): ?>
<div class="body">
<?php include_customizes($id, 'bodyTop') ?>
<?php echo $sf_data->getRaw('body') ?>
<?php include_customizes($id, 'bodyBottom') ?>
</div>
<?php endif; ?>

<form action="<?php echo url_for($options['url']) ?>" method="post"<?php if (!empty($options['isMultipart'])): ?> enctype="multipart/form-data"<?php endif; ?>>
<?php include_customizes($id, 'formTop') ?>

<table>
<?php
$forms = ($content instanceof sfForm) ? array($content) : $content;
foreach ($forms as $form)
{
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
           . '  <td><div id="'.$field->renderId().'">'.$field->render(array('class' => 'input_radio')).'</div></td>'."\n"
           . '</tr>'."\n";
        continue;
      }
    }
    elseif ($widget instanceof opWidgetFormDate)
    {
      echo '<tr>'."\n"
         . '  <th>'.$field->renderLabel().'</th>'."\n"
         . '  <td><div id="'.$field->renderId().'">'.$field->render().'</div></td>'."\n"
         . '</tr>'."\n";
      continue;
    }

    echo $field->renderRow($attributes);
  }
}
?>
<?php include_customizes($id, 'lastRow') ?>
</table>

<?php if (!empty($options['moreInfo'])) : ?>
<div class="block moreInfo">
<ul class="moreInfo">
<?php foreach ($options['moreInfo'] as $key => $value): ?>
<li><?php echo $value ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
<div class="operation">
<ul class="moreInfo button">
<li>
<?php foreach ($forms as $form) echo $form->renderHiddenFields() ?><input type="submit" class="input_submit" value="<?php echo $options['button'] ?>" />
</li>
</ul>
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
