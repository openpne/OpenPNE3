<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php use_helper('Javascript'); ?>

<h2><?php echo __('Navigation settings') ?></h2>

<p><?php echo __('When the following values are set in "Entry name", the setting value in "Term Configuration in this SNS" is reflected in the navigation.') ?></p>
<p><?php echo __('If it is not reflected, clear the caches in "Cache Clear" in "SNS".'); ?></p>
<div>
  <table>
    <tr>
      <th><?php echo __('%Friend%') ?></th>
      <td>%friend%</td>
    </tr>
    <tr>
      <th><?php echo __('%my_friend%') ?></th>
      <td>%my_friend%</td>
    </tr>
    <tr>
      <th><?php echo __('%Community%') ?></th>
      <td>%community%</td>
    </tr>
    <tr>
      <th><?php echo __('%Nickname%') ?></th>
      <td>%nickname%</td>
    </tr>
    <tr>
      <th><?php echo __('%Activity%') ?></th>
      <td>%activity%</td>
    </tr>
    <tr>
      <th><?php echo __('%post_activity%') ?></th>
      <td>%post_activity%</td>
    </tr>
  </table>
</div>

<?php foreach ($list as $type => $nav) : ?>
<h3><?php echo $type ?></h3>

<table id="type_<?php echo str_replace(' ', '_', $type) ?>">
<tr>
<th><?php echo __('URL') ?></th>
<?php $languages = sfConfig::get('op_supported_languages'); ?>
<?php foreach ($languages as $language): ?>
<th><?php echo __('Entry name').' ('.$language.')' ?></th>
<?php endforeach; ?>
<th colspan="2"><?php echo __('Operation') ?></th>
</tr>
<tbody>
<?php foreach ($nav as $form) : ?>
<tr id="type_<?php echo str_replace(' ', '_', $type) ?>_<?php echo $form->getObject()->getId() ?>"<?php if (!$form->isNew()) : ?> class="sortable"<?php endif; ?>>
<form action="<?php echo url_for('navigation/edit?app='.$sf_request->getParameter('app', 'pc')) ?>" method="post">
<td>
<?php echo $form->renderHiddenFields() ?>
<?php echo $form['uri']->renderError() ?>
<?php echo $form['uri']->render() ?>
</td>
<?php foreach ($languages as $language): ?>
<td>
<?php echo $form[$language]['caption']->renderError() ?>
<?php echo $form[$language]['caption']->render() ?>
</td>
<?php endforeach; ?>
<?php if ($form->isNew()) : ?>
<td colspan="2"><input type="submit" value="<?php echo __('Add') ?>" /></td>
</form>
<?php else : ?>
<td><input type="submit" value="<?php echo __('Edit') ?>" /></td>
</form>
<td>
<form action="<?php echo url_for('navigation/delete?app='.$sf_request->getParameter('app', 'pc').'&id='.$form->getObject()->getId()) ?>" method="post">
<?php echo $deleteForm ?>
<input type="submit" value="<?php echo __('Delete') ?>" />
</form>
</td>
<?php endif; ?>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php echo javascript_tag('
$("#type_'.str_replace(' ', '_', $type).' tbody").sortable({
  items: "> .sortable",
  update: function (event, ui) {
    var postData = $(this).sortable("serialize", { expression: /(type_'.str_replace(' ', '_', $type).')_(.+)/ });
    postData += "&'.urlencode($sortForm->getCSRFFieldName()).'='.urlencode($sortForm->getCSRFToken()).'";

    $.ajax({
      url: "'.url_for('navigation/sort').'",
      type: "POST",
      data: postData
    });
  }
});
') ?>

<?php endforeach; ?>
