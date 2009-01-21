<?php
$options = array(
  'title'   => __('コミュニティ検索'),
  'url'    => 'community/search',
  'button' => __('検索'),
  'moreInfo' => array(link_to(__('コミュニティ作成'), 'community/edit'))
);

op_include_form('searchCommunity', $filters, $options);
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
  'title'          => __('検索結果'),
  'pager'          => $pager,
  'link_to_page'   => 'community/search?page=%d',
  'link_to_detail' => 'community/home?id=%d',
  'list'           => $list,
);

op_include_parts('searchResultList', 'searchCommunityResult', $options);
?>
<?php else: ?>
<?php op_include_box('searchCommunityResult', __('該当するコミュニティはありませんでした。'), array('title' => __('検索結果'))) ?>
<?php endif; ?>
