<?php slot('submenu') ?>
<?php include_partial('design/submenu'); ?>
<?php end_slot() ?>

<?php use_helper('Javascript'); ?>

<h2>ナビ設定</h2>

<?php foreach ($list as $type => $nav) : ?>
<h3><?php echo $type ?></h3>

<table id="type_<?php echo str_replace(' ', '_', $type) ?>">
<tr>
<th>URL</th>
<th>項目名(ja_JP)</th>
<th colspan="2">操作</th>
</tr>
<?php foreach ($nav as $form) : ?>
<tbody id="type_<?php echo str_replace(' ', '_', $type) ?>_<?php echo $form->getObject()->getId() ?>"<?php if (!$form->isNew()) : ?> class="sortable"<?php endif; ?>>
<tr>
<td><form action="<?php echo url_for('navi/edit?app='.$app) ?>" method="post">
<?php echo $form->renderHiddenFields() ?>
<?php echo $form['uri']->render() ?></td>
<td><?php echo $form['ja_JP']['caption']->render() ?><?php echo $form['type']->render(array('value' => $type)) ?></td>
<?php if ($form->isNew()) : ?>
<td colspan="2"><input type="submit" value="追加" /></form></td>
<?php else : ?>
<td><?php echo $form['id']->render() ?>
<input type="submit" value="編集" /></form></td>
<td><form action="<?php echo url_for('navi/delete?id=' . $form->getObject()->getId()) ?>" method="post" /><input type="submit" value="削除" /></form></td>
<?php endif; ?>
</tr>
</tbody>
<?php endforeach; ?>
</table>

<?php echo sortable_element('type_'.str_replace(' ', '_', $type), array(
  'tag'  => 'tbody',
  'only' => 'sortable',
  'url' => 'navi/sort'
)) ?>

<?php endforeach; ?>
