<table>
<tr>
<th><?php echo $form['name']->renderLabel() ?></th>
<?php if (empty($forceAllowUserCommunity)) : ?>
<th><?php echo __($form['is_allow_member_community']->renderLabel(), array(), 'form_community') ?></th>
<?php endif; ?>
<th colspan="2"><?php echo __('操作') ?></th>
</tr>

<?php if ($categories): ?>
<?php foreach ($categories as $category): ?>
<form action="<?php echo url_for('community/categoryEdit?id='.$category->getId()) ?>" method="post">
<?php echo $category->getForm()->renderGlobalErrors() ?>
<tr>
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
<input type="submit" value="<?php echo __('追加') ?>" />
</td>
</tr>
</form>

</table>
