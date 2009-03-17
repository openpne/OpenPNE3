<table>
<tr>
<th><?php echo $form['name']->renderLabel() ?></th>
<th><?php echo $form['is_allow_user_community']->renderLabel() ?></th>
<th colspan="2"><?php echo __('操作') ?></th>
</tr>

<?php foreach ($categories as $category): ?>
<form action="<?php echo url_for('community/categoryEdit?id='.$category->getId()) ?>" method="post">
<?php echo $category->getForm()->renderGlobalErrors() ?>
<tr>
<?php foreach ($category->getForm() as $row) : ?>
<?php if (!$row->isHidden()) : ?>
<td><?php echo $row->renderError() ?><?php echo $row ?></td>
<?php endif; ?>
<?php endforeach; ?>

<td>
<?php foreach ($category->getForm() as $row) : ?>
<?php if ($row->isHidden()) : ?><?php echo $row ?><?php endif; ?>
<?php endforeach; ?>
<input type="submit" value="<?php echo __('編集') ?>" />
</td>
</form>
<td>
<form action="<?php echo url_for('community/categoryDelete?id='.$category->getId()) ?>" method="post">
<?php echo $deleteForm ?>
<input type="submit" value="<?php echo __('削除') ?>" />
</form>
</td>
</tr>
<?php endforeach; ?>

<form action="<?php echo url_for('community/categoryList') ?>" method="post">
<?php echo $form->renderGlobalErrors() ?>
<tr>
<?php foreach ($form as $row) : ?>
<?php if (!$row->isHidden()) : ?>
<td><?php echo $row->renderError() ?><?php echo $row ?></td>
<?php endif; ?>
<?php endforeach; ?>

<td colspan="2">
<?php foreach ($form as $row) : ?>
<?php if ($row->isHidden()) : ?><?php echo $row ?><?php endif; ?>
<?php endforeach; ?>
<input type="submit" value="<?php echo __('追加') ?>" />
</td>
</tr>
</form>

</table>
