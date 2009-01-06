<?php
$options = array(
  'form'   => $filters,
  'url'    => 'community/search',
  'button' => __('検索'),
  'moreInfo' => array(link_to(__('コミュニティ作成'), 'community/edit'))
);

include_box('searchCommunity', __('コミュニティ検索'), '', $options);
?>

<?php if ($pager->getNbResults()): ?>

<?php
$list = array();
foreach ($pager->getResults() as $key => $community)
{
  $list[$key] = array();
  $list[$key][__('コミュニティ名')] = $community->getName();
  $list[$key][__('説明文')] = $community->getConfig('description');
}

$options = array(
  'pager'          => $pager,
  'link_to_page'   => 'community/search?page=%d',
  'link_to_detail' => 'community/home?id=%d',
  'list'           => $list,
);

include_parts('searchResultList', 'searchCommunityResult', $options);
?>
<?php else: ?>
<?php include_box('searchCommunityResult', __('検索結果'), __('該当するコミュニティはありませんでした。')) ?>
<?php endif; ?>
