<div class="dparts manageList" id="manageList">
<div class="parts">

<div class="partsHeading"><h3><?php echo __('Plugin Developer Setting') ?></h3></div>

<?php op_include_pager_navigation($pager, '@package_manageMember?name='.$package->name); ?>

<div class="item"><table><tbody>
<?php foreach ($pager->getResults() as $item): ?>
<tr>
<td class="photo">
<?php echo link_to(image_tag_sf_image($item->getImageFilename(), array('size' => '76x76')), 'obj_member_profile', $item); ?><br />
<?php echo link_to((string)$item, 'obj_member_profile', $item) ?>
</td>

<td>
<form action="<?php echo url_for('package_manageMember', $package) ?>" method="post">
<?php $form = new opPluginMemberManageForm(array('member_id' => $item->id, 'package_id' => $package->id, 'position' => Doctrine::getTable('PluginMember')->getPosition($item->id, $package->id))); ?>
<?php echo $form['position']->render(); ?>
<?php echo $form->renderHiddenFields(); ?>
<input type="submit" value="<?php echo __('Save') ?>" class="input_submit" />
</form>
</td>

</tr>
<?php endforeach; ?>
</tbody></table></div>

<?php op_include_pager_navigation($pager, '@package_manageMember?name='.$package->name); ?>

</div>
</div>
