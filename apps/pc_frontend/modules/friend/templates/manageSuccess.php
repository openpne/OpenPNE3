<div class="dparts manageList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('My Friends Setting') ?></h3></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?></p></div>

<div class="item"><table><tbody>
<?php foreach ($pager->getResults() as $member): ?>
<?php $comp_vars = array('id' => $member->getId()) ?>

<tr>

<?php include_customizes('id_photo', 'before', $comp_vars) ?>
<td class="photo">
<?php echo link_to(image_tag_sf_image($member->getImageFilename(), array('size' => '76x76')), 'member/profile?id=' . $member->getId()); ?><br />
<?php echo link_to( $member->getName(), 'member/profile?id=' . $member->getId()) ?>
</td>
<?php include_customizes('id_photo', 'after', $comp_vars) ?>

<?php include_customizes('id_friend', 'before', $comp_vars) ?>
<td class="delete"><?php echo link_to(__('Delete from my friends.'), 'friend/unlink?id='.$member->getId()) ?></td>
<?php include_customizes('id_friend', 'after', $comp_vars) ?>
</tr>
<?php endforeach; ?>
</tbody></table></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?></p></div>

</div></div>
