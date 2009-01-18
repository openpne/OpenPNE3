<?php if (empty($option['form'])) : ?>
<div class="body">
<?php include_customizes($id, 'bodyTop') ?>
<?php echo $sf_data->getRaw('body') ?>
<?php include_customizes($id, 'bodyBottom') ?>
</div>
<?php endif; ?>

<?php if (isset($option['form'])) : ?>
<?php if (!empty($body)) : ?>
<div class="body">
<?php include_customizes($id, 'bodyTop') ?>
<?php echo $sf_data->getRaw('body') ?>
<?php include_customizes($id, 'bodyBottom') ?>
</div>
<?php endif; ?>
<?php $option_raw = $sf_data->getRaw('option') ?>
<form action="<?php echo url_for($option_raw['url']) ?>" method="post"<?php if (!empty($option['isMultipart'])) : ?> enctype="multipart/form-data"<?php endif; ?>>
<?php include_customizes($id, 'formTop') ?>

<table class="formTable">
<?php
$forms = ($option['form'] instanceof sfForm) ? array($option['form']) : $option['form'];
foreach ($forms as $form)
{
  foreach ($form as $field)
  {
    if ($field->isHidden()) continue;

    $widget = $field->getWidget();
    if ($widget instanceof sfWidgetFormInput)
    {
      $widget->setAttribute('class', sprintf('input_%s', $widget->getOption('type')));
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
    echo $field->renderRow();
  }
}
?>
<?php include_customizes($id, 'lastRaw') ?>
</table>

<?php if (!empty($option['moreInfo'])) : ?>
<div class="block moreInfo">
<ul class="moreInfo">
<?php foreach ($option['moreInfo'] as $key => $value) : ?>
<li><?php echo $option['moreInfo']->getRaw($key); ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>
<div class="operation">
<ul class="moreInfo button">
<li>
<?php foreach ($forms as $form) echo $form->renderHiddenFields() ?><input type="submit" class="input_submit" value="<?php echo $option['button'] ?>" />
</li>
</ul>
</div>
<?php include_customizes($id, 'formBottom') ?>
</form>
<?php endif; ?>
