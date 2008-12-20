<?php
$options = array(
  'form'   => $filters,
  'url'    => 'community/search',
  'button' => __('検索'),
  'moreInfo' => array(link_to(__('コミュニティ作成'), 'community/edit'))
);

include_box('searchCommunity', __('コミュニティ検索'), '', $options);
?>

<?php use_helper('Pagination', 'Date'); ?>

<?php if ($pager->getNbResults()): ?>
<div class="dparts searchResultList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('検索結果') ?></h3></div>

<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'community/search?page=%d'); ?></p></div>

<div class="block">
<?php foreach ($pager->getResults() as $community): ?>
<div class="ditem"><div class="item"><table><tbody><tr>
<td rowspan="4" class="photo"><?php echo link_to(image_tag_sf_image($community->getImageFilename(), array('size' => '76x76')), 'community/home?id='.$community->getId()) ?></td>
<th><?php echo __('コミュニティ名') ?></th><td><?php echo $community->getName() ?></td>
</tr><tr class="operation">
<td colspan="2"><span class="moreInfo"><?php echo link_to(__('詳細を見る'), 'community/home?id='.$community->getId()) ?></span></td>
</tr></tbody></table></div></div>
<?php endforeach; ?>
</div>
<div class="pagerRelative"><p class="number"><?php echo pager_navigation($pager, 'community/search?page=%d'); ?></p></div>
</div></div>
<?php else: ?>
<?php include_box('searchCommunityResult', __('検索結果'), __('該当するコミュニティはありませんでした。')) ?>
<?php endif; ?>
