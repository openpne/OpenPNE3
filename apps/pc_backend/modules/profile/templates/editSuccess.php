<?php use_helper('Javascript') ?>

<h2>
<?php if ($form->isNew()) : ?>
<?php echo __('Create new entry') ?>
<?php else : ?>
<?php echo __('Edit entry') ?>
<?php endif; ?>
</h2>

<div style="margin-bottom: 1em;">
<select id="original_preset">
  <option name="presetting"<?php if ($isPreset) : ?> selected="selected"<?php endif; ?>><?php echo __('Select from presets')?></option>
  <option name="original"<?php if (!$isPreset) : ?> selected="selected"<?php endif; ?>><?php echo __('Enter on your own')?></option>
</select>
</div>

<div id="preset"<?php if (!$isPreset): ?> style="display: none;"<?php endif ?>>
<?php if ($presetForm->isNew()): ?>
<?php if (0 < count($presetForm->getWidget('preset')->getOption('choices'))): ?>
<form action="<?php echo url_for('profile/edit?type=preset') ?>" method="post">
<table style="width: 50%;">
<?php echo $presetForm ?>
</table>
<input type="submit" value="<?php echo  __('Add') ?>" />
</form>
<?php else: ?>
<?php echo __('There is no preset profile.') ?>
<?php endif; ?>
<?php else: ?>
<form action="<?php echo url_for('profile/edit?type=preset&id='.$profile->getId()) ?>" method="post">
<table style="width: 50%;">
<?php echo $presetForm ?>
</table>
<input type="submit" value="<?php echo  __('Modify') ?>" />
</form>
<?php endif; ?>
</div>

<div id="original"<?php if ($isPreset): ?> style="display: none;"<?php endif ?>>
<?php if ($form->hasGlobalErrors()) : ?>
<ul>
<?php echo $form->renderGlobalErrors() ?>
</ul>
<?php endif; ?>

<?php if ($form->isNew()): ?>
<form action="<?php echo url_for('profile/edit') ?>" method="post">
<?php else : ?>
<form action="<?php echo url_for('profile/edit?id=' . $profile->getId()) ?>" method="post">
<?php endif; ?>

<?php $languages = sfConfig::get('op_supported_languages'); ?>
<table>
<tr>
<th></th>
<?php foreach ($languages as $language): ?>
<th><?php echo __('Settings for %language%', array('%language%' => $language))?></th>
<?php endforeach; ?>
</tr>
<tr>
<th><?php echo __('Caption') ?></th>
<?php foreach ($languages as $language): ?>
<td>
<?php echo $form[$language]['caption']->renderError() ?>
<?php echo $form[$language]['caption'] ?>
</td>
<?php endforeach; ?>
</tr>
<tr>
<th><?php echo __('Description') ?></th>
<?php foreach ($languages as $language): ?>
<td>
<?php echo $form[$language]['info']->renderError() ?>
<?php echo $form[$language]['info'] ?>
</td>
<?php endforeach; ?>
</tr>
</table>

<table id="common" style="width: 50%;">
<tr><th colspan="2"><?php echo __('General settings')?></th></tr>
<?php echo $form['name']->renderRow() ?>
<?php echo $form['is_required']->renderRow() ?>
<?php echo $form['is_edit_public_flag']->renderRow() ?>
<?php echo $form['default_public_flag']->renderRow() ?>
<?php echo $form['is_unique']->renderRow() ?>
<?php echo $form['is_disp_regist']->renderRow() ?>
<?php echo $form['is_disp_config']->renderRow() ?>
<?php echo $form['is_disp_search']->renderRow() ?>
<?php echo $form['form_type']->renderRow() ?>
</table>

<?php slot('advanced_settings_text') ?>
<table id="advanced">
<?php echo $form['value_type']->renderRow() ?>
<tr>
<th><?php echo $form['value_min']->renderLabel() ?>～<?php echo $form['value_max']->renderLabel() ?></th>
<td><?php echo $form['value_min']->render() ?>～<?php echo $form['value_max']->render() ?></td>
</tr>
<?php echo $form['value_regexp']->renderRow(array('class' => 'advanced')) ?>
</table>
<?php end_slot() ?>
<?php slot('advanced_settings_date') ?>
<table id="advanced">
<tr>
<th><?php echo $form['value_min']->renderLabel() ?>～<?php echo $form['value_max']->renderLabel() ?></th>
<td>
<ul>
<li><?php echo __('Please input in format: %format% . For example: %example%', array('%format%' => 'YYYY/MM/DD HH:MM:SS', '%example%' => '2009/01/01 23:59:21')) ?></li>
<li><?php echo __('Besides, you can use any particular string that can be interpreted by strtotime() function of PHP.') ?></li>
</ul>
<?php echo $form['value_min']->render() ?>～<?php echo $form['value_max']->render() ?>
</td>
</tr>
</table>
<?php end_slot() ?>

<?php if ($formType === 'input' || $formType === 'textarea'): ?>
<?php include_slot('advanced_settings_text') ?>
<?php elseif ($formType === 'date'): ?>
<?php include_slot('advanced_settings_date') ?>
<?php endif; ?>

<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo $form->isNew() ? __('Add') : __('Modify') ?>" />
</form>
</div>

<?php echo javascript_tag('
function changeAdvancedFormByFormType()
{
  if (document.getElementById("advanced"))
  {
    Element.remove("advanced");
  }

  var form_type = $F("profile_form_type");
  if (form_type == "input" || form_type == "textarea")
  {
    Insertion.After("common", "'.str_replace(array('"', "\n"), array('\"', ''), get_slot('advanced_settings_text')).'");
  }
  else if (form_type == "date")
  {
    Insertion.After("common", "'.str_replace(array('"', "\n"), array('\"', ''), get_slot('advanced_settings_date')).'");
  }
}

function changeOriginalAndPreset()
{
  var originalPreset = document.getElementById("original_preset");
  var selectedOption = originalPreset.options[originalPreset.selectedIndex];

  var selectedName = selectedOption.getAttribute("name");

  if (selectedName == "presetting")
  {
    Element.show(document.getElementById("preset"));
    Element.hide(document.getElementById("original"));
  }
  else if (selectedName == "original")
  {
    Element.show(document.getElementById("original"));
    Element.hide(document.getElementById("preset"));
  }
}

Event.observe("profile_form_type", "change", function(e){
  changeAdvancedFormByFormType();
});

Event.observe("original_preset", "change", function(e){
  changeOriginalAndPreset();
});
') ?>
