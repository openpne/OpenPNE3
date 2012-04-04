<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('RichTextarea Configuration') ?></h2>
<form action="<?php url_for('sns/richTextarea') ?>" method="post">
<table>
<?php echo $configForm ?>
</table>
<h3><?php echo __('Buttons Configuration') ?></h3>
<table id="button">
<thead>
<tr>
<th>ID</th>
<th><?php echo __('Button Image') ?></th>
<th><?php echo __('Caption') ?></th>
<th><?php echo __('Enabled') ?></th>
</tr>
</thead>
<tbody>
<?php foreach ($buttonConfigForm as $name => $button): ?>
<?php if (!$button->isHidden()): ?>
<tr id="button_<?php echo $name ?>" class="sortable">
<td><?php echo $name ?></td>
<td><?php echo isset($buttonConfig[$name]['imageURL']) ? image_tag($buttonConfig[$name]['imageURL']) : '&nbsp;' ?></td>
<td><?php echo isset($buttonConfig[$name]['caption']) ? __($buttonConfig[$name]['caption']) : '&nbsp;' ?></td>
<td><?php echo $button ?></td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
</tbody>
</table>
<?php echo $buttonConfigForm->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Edit') ?>" />
</form>
<?php use_helper('Javascript'); ?>
<?php echo javascript_tag('
$("#button tbody").sortable({
  items: "> .sortable",
  update: function (event, ui) {
    var postData = $(this).sortable("serialize", { expression: /(button)_(.+)/ });
    postData += "&'.urlencode($sortForm->getCSRFFieldName()).'='.urlencode($sortForm->getCSRFToken()).'";

    $.ajax({
      url: "'.url_for('sns/changeRichTextareaButtonOrder').'",
      type: "POST",
      data: postData
    });
  }
});
') ?>
