<?php
$options = array(
  'form'   => $filters,
  'url'    => 'member/search',
  'button' => __('検索'),
);

include_box('searchMember', __('メンバー検索'), '', $options);
?>

<?php use_helper('Date'); ?>

<?php if ($pager->getNbResults()): ?>
<?php
$list = array();
foreach ($pager->getResults() as $key => $member)
{
  $list[$key] = array();
  $list[$key][__('ニックネーム')] = $member->getName();
  if ($member->getProfile('self_intro'))
  {
    $list[$key][$member->getProfile('self_intro')->getCaption()] = nl2br($member->getProfile('self_intro'));
  }
  $list[$key][__('最終ログイン')] = distance_of_time_in_words($member->getLastLoginTime());
}

$options = array(
  'pager'          => $pager,
  'link_to_page'   => 'member/search?page=%d',
  'link_to_detail' => 'member/profile?id=%d',
  'list'           => $list,
);

include_parts('searchResultList', 'searchCommunityResult', $options);
?>
<?php else: ?>
<?php include_box('searchMemberResult', __('検索結果'), __('該当するメンバーはいませんでした。')) ?>
<?php endif; ?>
