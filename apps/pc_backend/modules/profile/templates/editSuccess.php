<?php use_helper('Javascript') ?>

<h2>
<?php if ($form->isNew()) : ?>
<?php echo __('プロフィール項目追加') ?>
<?php else : ?>
<?php echo __('プロフィール項目編集') ?>
<?php endif; ?>
</h2>

<div style="margin-bottom: 1em;">
<select id="original_preset">
  <option name="presetting"<?php if ($isPreset) : ?> selected="selected"<?php endif; ?>>プリセットから選ぶ</option>
  <option name="original"<?php if (!$isPreset) : ?> selected="selected"<?php endif; ?>>自分で入力する</option>
</select>
</div>

<div id="preset"<?php if (!$isPreset): ?> style="display: none;"<?php endif ?>>
<?php if ($presetForm->isNew()): ?>
<form action="<?php echo url_for('profile/edit?type=preset') ?>" method="post">
<?php else : ?>
<form action="<?php echo url_for('profile/edit?type=preset&id='.$profile->getId()) ?>" method="post">
<?php endif; ?>
<table>
<?php echo $presetForm ?>
</table>
<input type="submit" value="<?php echo $form->isNew() ? __('Add') : __('Modify') ?>" />
</form>
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
<table>
<tr><th colspan="2">ja_JP 用設定</th></tr>
<?php echo $form['ja_JP']['caption']->renderRow() ?>
<?php echo $form['ja_JP']['info']->renderRow() ?>
</table>

<table id="common">
<tr><th colspan="2">共通設定</th></tr>
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
<td>
<?php if ($form['value_min']->hasError()): ?>
<?php $error = $form['value_min']->getError() ?>
<?php $formatter = $form['value_min']->getParent()->getWidget()->getFormFormatter() ?>
<?php $msg = $form['value_min']->renderLabel().': '.$formatter->translate($error->getMessageFormat(), $error->getArguments()) ?>
<?php echo $formatter->formatErrorsForRow($msg) ?>
<?php endif ?>
<?php if ($form['value_max']->hasError()): ?>
<?php $error = $form['value_max']->getError() ?>
<?php $formatter = $form['value_max']->getParent()->getWidget()->getFormFormatter() ?>
<?php $msg = $form['value_max']->renderLabel().': '.$formatter->translate($error->getMessageFormat(), $error->getArguments()) ?>
<?php echo $formatter->formatErrorsForRow($msg) ?>
<?php endif ?>
<?php echo $form['value_min']->render() ?>～<?php echo $form['value_max']->render() ?>
</td>
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
<li><code>YYYY/MM/DD HH:MM:SS</code> 形式で入力（例：<code>2009/01/01 23:59:21</code>）</li>
<li>その他、 PHP の <code>strtotime()</code> 関数が解釈することのできる特殊な文字列が利用可能</li>
</ul>
<?php if ($form['value_min']->hasError()): ?>
<?php $error = $form['value_min']->getError() ?>
<?php $formatter = $form['value_min']->getParent()->getWidget()->getFormFormatter() ?>
<?php $msg = $form['value_min']->renderLabel().': '.$formatter->translate($error->getMessageFormat(), $error->getArguments()) ?>
<?php echo $formatter->formatErrorsForRow($msg) ?>
<?php endif ?>
<?php if ($form['value_max']->hasError()): ?>
<?php $error = $form['value_max']->getError() ?>
<?php $formatter = $form['value_max']->getParent()->getWidget()->getFormFormatter() ?>
<?php $msg = $form['value_max']->renderLabel().': '.$formatter->translate($error->getMessageFormat(), $error->getArguments()) ?>
<?php echo $formatter->formatErrorsForRow($msg) ?>
<?php endif ?>
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
