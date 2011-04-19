<table id="type_<?php echo $type ?>">
<tr>
<th><?php echo $form['name']->renderLabel() ?></th>
<?php if (empty($forceAllowUserCommunity)) : ?>
<th><?php echo __('Is Allow Member %community%', array('%community%' => $op_term['community']->titleize()), 'form_community') ?></th>
<?php endif; ?>
<th colspan="2"><?php echo __('Operation') ?></th>
</tr>

<?php if ($categories): ?>
<?php foreach ($categories as $category): ?>
<tbody id="type_<?php echo $type ?>_<?php echo $category->getId() ?>" class="sortable" style="position: relative;">
<tr>
<form action="<?php echo url_for('community/categoryEdit?id='.$category->getId()) ?>" method="post">
<?php echo $category->getForm()->renderGlobalErrors() ?>
<?php foreach ($category->getForm() as $key => $row) : ?>
<?php if (!$row->isHidden()) : ?>
<?php if (empty($forceAllowUserCommunity) || $key != 'is_allow_member_community') : ?>
<td><?php echo $row->renderError() ?><?php echo $row ?></td>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>

<td>
<?php foreach ($category->getForm() as $row) : ?>
<?php if ($row->isHidden()) : ?><?php echo $row ?><?php endif; ?>
<?php endforeach; ?>
<input type="submit" value="<?php echo __('Edit') ?>" />
</td>
</form>
<td>
<form action="<?php echo url_for('community/categoryDelete?id='.$category->getId()) ?>" method="post">
<?php echo $deleteForm ?>
<input type="submit" value="<?php echo __('Delete') ?>" />
</form>
</td>
</tr>
</tbody>
<?php endforeach; ?>
<?php endif; ?>

<form action="<?php echo url_for('community/categoryList') ?>" method="post">
<?php echo $form->renderGlobalErrors() ?>
<tr>
<?php foreach ($form as $key => $row) : ?>
<?php if (!$row->isHidden()) : ?>
<?php if (empty($forceAllowUserCommunity) || $key != 'is_allow_member_community') : ?>
<td><?php echo $row->renderError() ?><?php echo $row ?></td>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>

<td colspan="2">
<?php foreach ($form as $row) : ?>
<?php if ($row->isHidden()) : ?><?php echo $row ?><?php endif; ?>
<?php endforeach; ?>
<input type="submit" value="<?php echo __('Add') ?>" />
</td>
</tr>
</form>

</table>

<?php $form = new BaseForm() ?>
<?php echo sortable_element('type_'.$type, array(
  'tag'  => 'tbody',
  'only' => 'sortable',
  'url'  => 'community/categorySort',
  'with' => 'Sortable.serialize("type_'.$type.'")+"&'.urlencode($form->getCSRFFieldName()).'='.urlencode($form->getCSRFToken()).'"',
)) ?>
