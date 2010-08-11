<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<?php use_helper('Javascript'); ?>

<h2><?php echo __('ナビ設定') ?></h2>

<?php foreach ($list as $type => $nav) : ?>
<h3><?php echo $type ?></h3>

<table id="type_<?php echo str_replace(' ', '_', $type) ?>">
<tr>
<th><?php echo __('URL') ?></th>
<th><?php echo __('項目名(ja_JP)') ?></th>
<th colspan="2"><?php echo __('操作') ?></th>
</tr>
<?php foreach ($nav as $form) : ?>
<tbody id="type_<?php echo str_replace(' ', '_', $type) ?>_<?php echo $form->getObject()->getId() ?>"<?php if (!$form->isNew()) : ?> class="sortable"<?php endif; ?>>
<tr>
<form action="<?php echo url_for('navigation/edit?app='.$sf_request->getParameter('app', 'pc')) ?>" method="post">
<td>
<?php echo $form->renderHiddenFields() ?>
<?php echo $form['uri']->render() ?></td>
<td><?php echo $form['ja_JP']['caption']->render() ?></td>
<?php if ($form->isNew()) : ?>
<td colspan="2"><input type="submit" value="<?php echo __('追加') ?>" /></td>
</form>
<?php else : ?>
<td><input type="submit" value="<?php echo __('編集') ?>" /></td>
</form>
<td>
<form action="<?php echo url_for('navigation/delete?app='.$sf_request->getParameter('app', 'pc').'&id='.$form->getObject()->getId()) ?>" method="post">
<?php echo $deleteForm ?>
<input type="submit" value="<?php echo __('削除') ?>" />
</td>
<?php endif; ?>
</tr>
</tbody>
<?php endforeach; ?>
</table>

<?php echo sortable_element('type_'.str_replace(' ', '_', $type), array(
  'tag'  => 'tbody',
  'only' => 'sortable',
  'url'  => 'navigation/sort',
  'with' => 'Sortable.serialize("type_'.str_replace(' ', '_', $type).'")+"&'.urlencode($sortForm->getCSRFFieldName()).'='.urlencode($sortForm->getCSRFToken()).'"',
)) ?>

<?php endforeach; ?>
