<?php use_helper('Javascript') ?>

<h2><?php echo __('プロフィール項目追加') ?></h2>

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
<?php echo $form['is_public_flag_edit']->renderRow() ?>
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
<li><code>YYYY/MM/DD HH:MM:SS</code> 形式で入力（例：<code>2009/01/01 23:59:21</code>）</li>
<li>その他、 PHP の <code>strtotime()</code> 関数が解釈することのできる特殊な文字列が利用可能</li>
</ul>
<?php echo $form['value_min']->render() ?>～<?php echo $form['value_max']->render() ?>
</td>
</tr>
</table>
<?php end_slot() ?>

<?php if ($form->isNew()) : ?>
<?php include_slot('advanced_settings_text') ?>
<?php else: ?>
<?php if ($profile->getFormType() === 'input' || $profile->getFormType() == 'textarea'): ?>
<?php include_slot('advanced_settings_text') ?>
<?php elseif ($profile->getFormType() === 'date'): ?>
<?php include_slot('advanced_settings_date') ?>
<?php endif; ?>
<?php endif; ?>

<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('追加する') ?>" />
</form>

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

Event.observe(window, "load", function(e){
  changeAdvancedFormByFormType();
});

Event.observe("profile_form_type", "change", function(e){
  changeAdvancedFormByFormType();
});
') ?>
