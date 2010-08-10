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
<?php foreach ($buttonConfigForm as $name => $button): ?>
<?php if (!$button->isHidden()): ?>
<tbody id="button_<?php echo $name ?>" class="sortable">
<tr>
<td><?php echo $name ?></td>
<td><?php echo isset($buttonConfig[$name]['imageURL']) ? image_tag($buttonConfig[$name]['imageURL']) : '&nbsp;' ?></td>
<td><?php echo isset($buttonConfig[$name]['caption']) ? __($buttonConfig[$name]['caption']) : '&nbsp;' ?></td>
<td><?php echo $button ?></td>
</tr>
</tbody>
<?php endif; ?>
<?php endforeach; ?>
</table>
<?php echo $buttonConfigForm->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Edit') ?>" />
</form>
<?php use_helper('Javascript'); ?>
<?php echo sortable_element('button', array(
  'tag'  => 'tbody',
  'only' => 'sortable',
  'format' => '/^button_(.*)$/',
  'url'  => 'sns/changeRichTextareaButtonOrder',
  'with' => 'Sortable.serialize("button")+"&'.urlencode($sortForm->getCSRFFieldName()).'='.urlencode($sortForm->getCSRFToken()).'"',
)) ?>
