<?php use_helper('Javascript'); ?>
<h2><?php echo __('Profile setting') ?></h2>

<h3><?php echo __('Entry list') ?></h3>
<p><?php echo link_to(__('Create new entry'), 'profile/edit') ?></p>
<table id="profiles">
<thead><tr>
<th colspan="2"><?php echo __('Operation')?></th>
<th>ID</th>
<th><?php echo __('Entry name')?></th>
<th><?php echo __('Identification name')?></th>
<th><?php echo __('Required')?></th>
<th><?php echo __('Public setting')?></th>
<th><?php echo __('Public default setting')?></th>
<th><?php echo __('Duplication')?></th>
<th><?php echo __('Input type')?></th>
<th><?php echo __('Option')?></th>
<th><?php echo __('Registry')?></th>
<th><?php echo __('Change')?></th>
<th><?php echo __('Search')?></th>
</tr></thead>
<?php foreach ($profiles as $value): ?>
<tbody id="profile_<?php echo $value->getId() ?>" class="sortable">
<tr>
<td><?php echo link_to(__('Edit'), 'profile/edit?id=' . $value->getId()) ?></td>
<td><?php echo link_to(__('Delete'), 'profile/delete?id=' . $value->getId()) ?></td>
<td><?php echo $value->getId() ?></td>
<?php if ($value->isPreset()) : ?>
<?php $presetConfig = $value->getPresetConfig(); ?>
<td><?php echo __($presetConfig['Caption']) ?></td>
<?php else: ?>
<td><?php echo $value->getCaption() ?></td>
<?php endif; ?>
<td><?php echo $value->getName() ?></td>
<td><?php echo ($value->getIsRequired() ? '○' : '×') ?></td>
<td><?php echo ($value->getIsEditPublicFlag() ? '○' :'×') ?></td>
<td>
<?php
if (ProfileTable::PUBLIC_FLAG_FRIEND == $value->getDefaultPublicFlag())
{
  echo __('My Friends');
}
else
{
  echo (Doctrine::getTable('Profile')->getPublicFlag($value->getDefaultPublicFlag()));
}
?></td>
<td><?php echo ($value->getIsUnique() ? '×' :'○') ?></td>
<td><?php echo $value->getFormType() ?></td>
<td>
<?php if (!$value->isPreset() && ($value->getFormType() == 'radio' || $value->getFormType() == 'checkbox' || $value->getFormType() == 'select')) : ?>
<?php echo link_to(__('List'), 'profile/list', array('anchor' => $value->getName())) ?>
<?php else: ?>
-
<?php endif; ?>
</td>
<td><?php echo ($value->getIsDispRegist() ? '○' :'×') ?></td>
<td><?php echo ($value->getIsDispConfig() ? '○' :'×') ?></td>
<td><?php echo ($value->getIsDispSearch() ? '○' : '') ?></td>
</tr>
</tbody>
<?php endforeach; ?>
</table>
<?php echo sortable_element('profiles',array(
  'tag' => 'tbody',
  'url' => 'profile/sortProfile'
)) ?>

<h3><?php echo __('Option list')?></h3>
<?php $selectionCount = 0; ?>
<?php foreach ($profiles as $value): ?>
<?php if (!$value->isPreset() && ($value->getFormType() == 'radio' || $value->getFormType() == 'checkbox' || $value->getFormType() == 'select')) : ?>
<?php $selectionCount++; ?>
<h4><a name="<?php echo $value->getName() ?>"><?php echo $value->getCaption() ?></a></h4>
<table id="profile_options_<?php echo $value->getId() ?>">
<thead><tr>
<th>ID</th>
<?php $languages = sfConfig::get('op_supported_languages'); ?>
<?php foreach ($languages as $language): ?>
<th><?php echo __('Option name (%language%)', array('%language%' => $language)) ?></th>
<?php endforeach; ?>
<th colspan="2"><?php echo __('Operation')?></th>
</tr></thead>
<?php foreach ($option_form[$value->getId()] as $form) : ?>
<?php if (!$form->getObject()->isNew()) : ?>
<tbody id="profile_option_<?php echo $form->getObject()->getId() ?>" class="sortable">
<?php else: ?>
<tbody>
<?php endif; ?>
<tr>
<form action="<?php echo url_for('profile/editOption?id=' . $form->getObject()->getId()) ?>" method="post">
<td><?php echo ($form->getObject()->isNew() ? '-' : $form->getObject()->getId()) ?></td>
<?php foreach ($languages as $language): ?>
<td>
<?php echo $form[$language]['value']->renderError() ?>
<?php echo $form[$language]['value']->render() ?>
</td>
<?php endforeach; ?>
<?php if ($form->getObject()->isNew()) : ?>
<td colspan="2">
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Add new option')?>" />
</td>
</form>
<?php else : ?>
<td>
<?php echo $form->renderHiddenFields() ?>
<input type="submit" value="<?php echo __('Save')?>" />
</td>
</form>
<td>
<form action="<?php echo url_for('profile/deleteOption?id=' . $form->getObject()->getId()) ?>" method="post">
<?php echo $form['id']->render(), "\n" ?>
<?php echo $form['profile_id']->render(), "\n" ?>
<input type="submit" value="<?php echo __('Delete') ?>" />
</form>
</td>
<?php endif; ?>
</tr>
</tbody>
<?php endforeach; ?>
</table>
<?php echo sortable_element('profile_options_'.$value->getId(),array(
  'tag'  => 'tbody',
  'only' => 'sortable',
  'url'  => 'profile/sortProfileOption'
)) ?>
<?php endif; ?>
<?php endforeach; ?>
<?php if (!$selectionCount): ?>
<p><?php echo __('No entries can set options.')?></p>
<?php endif; ?>
