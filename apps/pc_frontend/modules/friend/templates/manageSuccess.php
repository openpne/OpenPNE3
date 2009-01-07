<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('マイフレンド管理') ?></h3></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?></p></div>

<div class="item"><table><tbody>
<?php foreach ($pager->getResults() as $member): ?>
<?php $sf_user->setAttribute('id', $member->getId()) ?>

<tr>

<?php include_customizes('id_photo', 'before') ?>
<td class="photo">
<?php echo link_to(image_tag_sf_image($member->getImageFilename(), array('size' => '76x76')), 'member/profile?id=' . $member->getId()); ?><br />
<?php echo link_to( $member->getName(), 'member/profile?id=' . $member->getId()) ?>
</td>
<?php include_customizes('id_photo', 'after') ?>

<?php include_customizes('id_friend', 'before') ?>
<td><?php echo link_to(__('フレンドから外す'), 'friend/unlink?id='.$member->getId()) ?></td>
<?php include_customizes('id_friend', 'after') ?>
</tr>
<?php endforeach; ?>
</tbody></table></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'friend/list?page=%d&id=' . $sf_params->get('id')); ?></p></div>

</div></div>
