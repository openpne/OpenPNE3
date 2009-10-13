<?php use_helper('Date') ?>
<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Favorite') ?></h3></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'favorite/list?page=%d'); ?></p></div>

<div class="block">

<?php foreach ($members as $member): ?>
<div class="ditem"><div class="item">
<table><tbody>

<tr>
<td rowspan="3" class="photo">
<?php echo link_to(image_tag_sf_image($member->getImageFilename(), array('size' => '76x76')), 'member/profile?id=' . $member->getId()); ?><br />
</td>
<th><?php echo __('Nickname') ?></th>
<td><?php echo $member->getName() ?></td>
</tr>

<?php if ($member->getProfile('self_intro')): ?>
<tr>
<th><?php echo $member->getProfile('self_intro')->getCaption() ?></th>
<td><?php echo nl2br($member->getProfile('self_intro')) ?></td>
</tr>
<?php endif ?>

<tr class="operation">
<th><?php echo __('The last login') ?></th>
<td>
<span class="text"><?php echo distance_of_time_in_words($member->getLastLoginTime()) ?></span>
<span class="moreInfo">
<?php echo link_to(__('Show detail'), 'member/profile?id=' . $member->getId()) ?>
 <?php echo link_to(__('Delete'), 'favorite/delete?id=' . $member->getId()) ?>
</span>
</td>
</tr>

</tbody></table>
</div></div>
<?php endforeach; ?>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'favorite/list?page=%d'); ?></p></div>
</div></div></div>
