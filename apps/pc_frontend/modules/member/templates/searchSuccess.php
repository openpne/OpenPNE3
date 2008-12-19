<?php
$options = array(
  'form'   => $filters,
  'url'    => 'member/search',
  'button' => __('検索'),
);

include_box('searchMember', __('メンバー検索'), '', $options);
?>

<?php use_helper('Pagination', 'Date'); ?>

<?php if ($pager->getNbResults()): ?>
<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('検索結果') ?></h3></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'member/search?page=%d'); ?></p></div>

<div class="block">
<?php foreach ($pager->getResults() as $member): ?>
<div class="ditem"><div class="item"><table><tbody><tr>
<td rowspan="4" class="photo"><?php echo link_to(image_tag_sf_image($member->getImageFilename(), array('size' => '76x76')), 'member/profile?id='.$member->getId()) ?></td>
<th><?php echo __('ニックネーム') ?></th><td><?php echo $member->getName() ?></td>
</tr><tr>
<th><?php echo $member->getProfile('self_intro')->getCaption() ?></th><td><?php echo $member->getProfile('self_intro') ?></td>
</tr><tr class="operation">
<th><?php echo __('最終ログイン') ?></th><td><span class="text"><?php echo distance_of_time_in_words($member->getLastLoginTime()) ?></span><span class="moreInfo"><?php echo link_to(__('詳細を見る'), 'member/profile?id='.$member->getId()) ?></span></td>
</tr></tbody></table></div></div>
<?php endforeach; ?>
</div>
<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'member/search?page=%d'); ?></p></div>
</div></div>
<?php else: ?>
<?php include_box('searchMemberResult', __('検索結果'), __('該当するメンバーはいませんでした。')) ?>
<?php endif; ?>
